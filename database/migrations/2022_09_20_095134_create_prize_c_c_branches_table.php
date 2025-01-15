<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrizeCCBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prize_c_c_branches', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('prize_c_c_uuid');
            $table->integer('branch_id');
            $table->integer('total_qty');
            $table->integer('remain_qty');
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
        Schema::dropIfExists('prize_c_c_branches');
    }
}
