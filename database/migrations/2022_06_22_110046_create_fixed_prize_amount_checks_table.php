<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixedPrizeAmountChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_prize_amount_checks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('promotion_uuid');
            $table->uuid('sub_promotion_uuid');
            $table->string('fixed_prize_gp_code');
            $table->integer('fixed_prize_name');
            $table->integer('fixed_prize_qty');
            $table->integer('fixed_prize_ticket_amount');
            $table->integer('fixed_prize_type');
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
        Schema::dropIfExists('fixed_prize_amount_checks');
    }
}
