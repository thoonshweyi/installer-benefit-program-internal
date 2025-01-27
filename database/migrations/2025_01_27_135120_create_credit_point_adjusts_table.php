<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditPointAdjustsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_point_adjusts', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->tinyInteger('branch_id');
            $table->string('document_no');
            $table->string("installer_card_card_number");
            $table->integer('total_points_adjusted');
            $table->decimal('total_adjust_value', 19, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->dateTime('adjust_date');
            $table->uuid('approved_by')->nullable();
            $table->dateTime("approved_date")->nullable();
            // $table->foreign('approved_by')->references('uuid')->on('users')->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->text('remark')->nullable();
            $table->text('bm_remark')->nullable();
            $table->uuid("user_uuid");
            $table->uuid("collection_transaction_uuid")->nullable();
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
        Schema::dropIfExists('credit_point_adjusts');
    }
}
