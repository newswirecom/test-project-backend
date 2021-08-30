<?php

use Carbon\Carbon;
use App\Models\Job;
use Ramsey\Uuid\Uuid;
use joshtronic\LoremIpsum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class Jobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ($this->hasMysqlCommand() && $this->hasBufferCommand()) {
            $pipe = popen(vsprintf('buffer/buffer -m 2m -s 512k | mysql -h %s -P %s -u %s --password=%s %s', [
                escapeshellarg(config('database.connections.mysql.host')),
                escapeshellarg(config('database.connections.mysql.port')),
                escapeshellarg(config('database.connections.mysql.username')),
                escapeshellarg(config('database.connections.mysql.password')),
                escapeshellarg(config('database.connections.mysql.database')),
            ]), 'w');

            $pipeSql = function($sql) use ($pipe) {
                fwrite($pipe, $sql);
            };
        } else {
            $pipeSql = function($sql) {
                DB::unprepared($sql);
            };
        }

        ini_set('memory_limit', '2048M');

        Schema::create('jobs', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->bigInteger('customer_id')->unsigned();
            $table->dateTime('scheduled_at');
            $table->bigInteger('assigned_worker_id')->nullable();
            $table->boolean('is_deleted');
            $table->text('description');

            $table->index(['scheduled_at', 'assigned_worker_id', 'is_deleted'], 'idx_queue');
        });

        $now = time();
        $lipsum = new LoremIpsum();

        $output = new ConsoleOutput();
        $progress = new ProgressBar($output, 1000);
        $progress->start();

        $pipeSql(<<<SQL
            ALTER TABLE jobs DISABLE KEYS;
            SET UNIQUE_CHECKS = 0;
        SQL);

        $descriptions = [];
        for ($i = 0; $i < 1000; $i++) {
            $descriptions[$i] = $lipsum->words(10);
        }

        for ($i = 0; $i < 1000; $i++) {

            $inserts = [];

            for ($j = 0; $j < 10000; $j++) {
                $scheduledAt = time() + 86400 - rand(0, 62208000);
                $isAssignedChance = rand(1, 100) > 1;

                $inserts[] = sql_insert_line([
                    'id' => Uuid::uuid4()->toString(),
                    'customer_id' => $j + 1,
                    'assigned_worker_id' => ($scheduledAt < $now && $isAssignedChance) ? (($j % 50) + 1) : null,
                    'is_deleted' => (int) !$isAssignedChance,
                    'scheduled_at' => date('Y-m-d H:i:s', $scheduledAt),
                    'description' => $descriptions[$j % 1000],
                ]);
            }

            $insertsString = comma_separate($inserts);
            unset($inserts);

            $sql = <<<SQL
                INSERT INTO jobs (id, customer_id, assigned_worker_id, is_deleted, scheduled_at, description)
                VALUES {$insertsString};
            SQL;

            $pipeSql($sql);

            $progress->advance();
        }

        $pipeSql(<<<SQL
            ALTER TABLE jobs ENABLE KEYS;
            SET UNIQUE_CHECKS = 1;
        SQL);

        if (isset($pipe)) {
            fclose($pipe);
        }

        $progress->clear();
    }

    /**
     * Does this system have mysql command line?
     *
     * @return boolean
     */
    protected function hasMysqlCommand()
    {
        $result = 0;

        system('mysql -V >/dev/null || false', $result);

        return $result === 0;
    }

    /**
     * Get buffer command
     *
     * @return string
     */
    protected function hasBufferCommand()
    {
        $result = 0;

        system('echo | buffer/buffer -m 2m -s 512k', $result);

        return $result === 0;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
