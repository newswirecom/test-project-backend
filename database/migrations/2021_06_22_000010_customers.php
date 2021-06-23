<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class Customers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ini_set('memory_limit', '1024M');

        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->text('name');
            $table->text('email');
            $table->timestamps();
        });

        $faker = Faker\Factory::create();
        $now = Carbon::now();
        $inserts = [];

        for ($i = 0; $i < 10000; $i++) {
            $inserts[] = sql_insert_line([
                'name' => $faker->name,
                'email' => $faker->email,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $insertsString = comma_separate($inserts);
        unset($inserts);

        $sql = <<<SQL

            ALTER TABLE customers DISABLE KEYS;
            SET UNIQUE_CHECKS = 0;
            SET AUTOCOMMIT = 0;

            INSERT INTO customers (name, email, created_at)
            VALUES {$insertsString};

            ALTER TABLE customers ENABLE KEYS;

            COMMIT;

            SET UNIQUE_CHECKS = 1;
            SET AUTOCOMMIT = 1;

        SQL;

        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
