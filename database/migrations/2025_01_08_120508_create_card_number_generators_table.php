<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardNumberGeneratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_number_generators', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid");
            $table->tinyInteger('branch_id');
            $table->string('document_no');
            $table->tinyInteger('to_branch_id');
            $table->unsignedBigInteger('quantity');
            $table->tinyInteger('random');
            $table->enum('status', ['pending', 'approved', 'rejected','exported'])->default('pending');
            $table->uuid('prepare_by');
            $table->uuid('approved_by')->nullable();
            $table->dateTime("approved_date")->nullable();
            $table->text('remark')->nullable();
            $table->text('mkt_mgr_remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_number_generators');
    }
}
