<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNRCNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nrc_names', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('nrc_number_id');
            $table->foreign('nrc_number_id')->references('id')->on('nrc_numbers')->onDelete('cascade');
            $table->string('district');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nrc_names');
    }
}
