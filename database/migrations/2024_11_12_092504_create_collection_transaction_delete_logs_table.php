<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionTransactionDeleteLogsTable extends Migration
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
        Schema::create('collection_transaction_delete_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('action_user_uuid')->nullable();
            $table->tinyInteger('action_branch_id')->nullable();
            $table->uuid('old_collection_transaction_uuid');
            $table->uuid('point_promotion_uud')->nullable();
            $table->decimal('points_award_rate', 8, 2)->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('document_no')->nullable();
            $table->string('installer_card_card_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->decimal('total_sale_cash_amount', 15, 2)->nullable();
            $table->integer('total_points_collected')->nullable();
            $table->decimal('total_save_value', 15, 2)->nullable();
            $table->dateTime('collection_date')->nullable();
            $table->uuid('user_uuid')->nullable();
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
        Schema::dropIfExists('collection_transaction_delete_logs');
    }
}
