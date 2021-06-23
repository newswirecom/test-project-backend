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
        ini_set('memory_limit', '1024M');

        Schema::create('jobs', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->bigInteger('customer_id')->unsigned();
            $table->dateTime('scheduled_at');
            $table->bigInteger('assigned_worker_id')->nullable();
            $table->boolean('is_deleted');
            $table->text('description');

            $table->index(['scheduled_at', 'assigned_worker_id', 'is_deleted'], 'idx_queue');
        });

        $now = Carbon::now();
        $lipsum = new LoremIpsum();

        $output = new ConsoleOutput();
        $progress = new ProgressBar($output, 1000);
        $progress->start();

        for ($i = 0; $i < 1000; $i++) {

            $inserts = [];

            for ($j = 0; $j < 10000; $j++) {
                $scheduledAt = Carbon::createFromTimestamp(time() + 86400 - rand(0, 62208000));
                $isAssignedChance = rand(1, 100) > 1;

                $inserts[] = sql_insert_line([
                    'id' => Uuid::uuid4()->toString(),
                    'customer_id' => rand(1, 10000),
                    'assigned_worker_id' => ($scheduledAt < $now && $isAssignedChance) ? rand(1, 50) : null,
                    'is_deleted' => (int) !$isAssignedChance,
                    'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s'),
                    'description' => $lipsum->words(10),
                ]);
            }

            $insertsString = comma_separate($inserts);
            unset($inserts);

            $sql = <<<SQL

                ALTER TABLE jobs DISABLE KEYS;
                SET UNIQUE_CHECKS = 0;
                SET AUTOCOMMIT = 0;

                INSERT INTO jobs (id, customer_id, assigned_worker_id, is_deleted, scheduled_at, description)
                VALUES {$insertsString};

                ALTER TABLE jobs ENABLE KEYS;

                COMMIT;

                SET UNIQUE_CHECKS = 1;
                SET AUTOCOMMIT = 1;

            SQL;

            DB::unprepared($sql);

            $progress->advance();
        }

        $progress->clear();
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
