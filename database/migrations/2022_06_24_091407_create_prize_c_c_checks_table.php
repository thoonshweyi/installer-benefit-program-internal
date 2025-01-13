<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrizeCCChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prize_c_c_checks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('prize_item_uuid');
            $table->uuid('promotion_uuid');
            $table->uuid('sub_promotion_uuid');
            $table->string('ticket_image');
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
        Schema::dropIfExists('prize_c_c_checks');
    }
}
