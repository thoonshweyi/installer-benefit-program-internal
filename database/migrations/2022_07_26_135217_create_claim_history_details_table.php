<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimHistoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_history_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('claim_history_uuid');
            $table->integer('times');
            $table->uuid('price_cc_check_uuid');
            $table->uuid('prize_item_uuid');
            $table->string('serial_no');
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
        Schema::dropIfExists('claim_history_details');
    }
}
