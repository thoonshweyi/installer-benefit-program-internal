<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWinningChanceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winning_chance_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('c_c_winning_chance_uuid');
            $table->integer('winning_percentage');
            $table->string('action');
            $table->uuid('user_uuid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('winning_chance_histories');
    }
}
