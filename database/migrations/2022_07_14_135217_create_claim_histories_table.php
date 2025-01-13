<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('ticket_header_uuid');
            $table->uuid('promotion_uuid');
            $table->uuid('sub_promotion_uuid');
            $table->string('one_qty_amount');
            $table->tinyInteger('invoice_check_type')->default(1);
            $table->tinyInteger('prize_check_type')->default(1);
            $table->integer('valid_qty');
            $table->integer('choose_qty')->nullable();
            $table->integer('remain_choose_qty')->nullable();
            $table->tinyInteger('choose_status')->default(1);
            $table->integer('remain_claim_qty')->nullable();
            $table->tinyInteger('claim_status')->default(1);
            $table->datetime('claimed_at')->nullable();
            $table->tinyInteger('print_status')->default(1);
            $table->datetime('printed_at')->nullable();
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
        Schema::dropIfExists('claim_histories');
    }
}
