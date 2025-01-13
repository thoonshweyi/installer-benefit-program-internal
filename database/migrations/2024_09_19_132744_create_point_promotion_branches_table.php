<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointPromotionBranchesTable extends Migration
{
    // public function __construct()
    // {
    //     $this->connection = 'centralpgsql';
    // }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_promotion_branches', function (Blueprint $table) {
            $table->id();
            $table->uuid('point_promotion_uuid');
            $table->unsignedBigInteger('branch_id');
            $table->foreign('point_promotion_uuid')->references('uuid')->on('point_promotions')->onDelete('cascade');
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
        Schema::dropIfExists('point_promotion_branches');
    }
}
