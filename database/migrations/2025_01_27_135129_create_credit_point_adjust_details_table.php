<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditPointAdjustDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_point_adjust_details', function (Blueprint $table) {
            $table->id();
            $table->decimal("point_based",19,2);
            $table->integer("points_adjusted");
            $table->decimal("amount_adjusted",19,2);
            $table->uuid("installer_card_point_uuid")->nullable();
            $table->uuid("point_adjust_uuid");
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
        Schema::dropIfExists('credit_point_adjust_details');
    }
}
