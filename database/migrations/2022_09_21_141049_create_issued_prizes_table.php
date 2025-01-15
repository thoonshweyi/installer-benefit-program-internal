<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuedPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issued_prizes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('ticket_header_uuid');
            $table->uuid('promotion_uuid');
            $table->uuid('sub_promotion_uuid');
            $table->integer('branch_id');
            $table->string('serial_no');
            $table->timestamp('prize_date');
            $table->string('prize_code');
            $table->uuid('customer_uuid');
            $table->integer('prize_qty');
            $table->integer('prize_amount');
            $table->integer('sale_amount');
            $table->integer('prize_type');
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
        Schema::dropIfExists('issued_prizes');
    }
}
