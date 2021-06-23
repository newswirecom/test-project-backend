<?php

use Carbon\Carbon;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class Workers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ini_set('memory_limit', '1024M');

        Schema::create('workers', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->text('name');
            $table->text('email');
            $table->timestamps();
        });

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
            Worker::create([
                'name' => $faker->name,
                'email' => $faker->email,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workers');
    }
}
