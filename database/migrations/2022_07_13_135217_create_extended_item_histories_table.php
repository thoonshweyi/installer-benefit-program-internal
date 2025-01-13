<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtendedItemHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extended_item_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('prize_c_c_check_uuid');
            $table->integer('branch_id');
            $table->integer('extended_qty');
            $table->tinyInteger('action');
            $table->uuid('extended_by');
            $table->timestamps();
            $table->foreign('extended_by')->references('uuid')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extended_item_histories');
    }
}
