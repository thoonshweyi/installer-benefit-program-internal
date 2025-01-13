<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrizeTicketChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prize_ticket_checks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('promotion_uuid');
            $table->uuid('sub_promotion_uuid');
            $table->string('ticket_prize_image');
            $table->integer('ticket_prize_qty');
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
        Schema::dropIfExists('prize_ticket_check');
    }
}
