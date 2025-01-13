<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionChangeLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_change_log', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('promotion_uuid');
            $table->timestamp('date');
            $table->uuid('user_uuid');
            $table->string('old_info');
            $table->string('new_info');
            $table->string('reason');
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
        Schema::dropIfExists('promotion_change_log');
    }
}
