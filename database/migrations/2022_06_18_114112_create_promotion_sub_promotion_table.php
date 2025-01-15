<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionSubPromotionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_sub_promotion', function (Blueprint $table) {
            $table->id();
            $table->uuid('promotion_uuid');
            $table->uuid('sub_promotion_uuid');
            $table->tinyInteger('invoice_check_type')->default(1);
            $table->tinyInteger('prize_check_type')->default(1);
            $table->tinyInteger('invoice_check_status')->nullable();
            $table->tinyInteger('prize_check_status')->nullable();
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
        Schema::dropIfExists('promotion_sub_promotion');
    }
}
