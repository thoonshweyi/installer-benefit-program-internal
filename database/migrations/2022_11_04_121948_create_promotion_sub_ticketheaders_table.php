<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionSubTicketheadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_sub_ticketheaders', function (Blueprint $table) {
            $table->id();
            $table->uuid('promotion_uuid');
            $table->uuid('sub_promotion_uuid');
            $table->uuid('ticket_header_uuid');
            $table->unsignedBigInteger('invoice_id');
            $table->string('invoice_no');
            $table->string('gbh_customer_id');
            $table->integer('valid_amount');
            $table->string('status');
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
        Schema::dropIfExists('promotion_sub_ticketheader');
    }
}
