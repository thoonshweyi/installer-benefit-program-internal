<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallerCardTransferLogsTable extends Migration
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
        Schema::create('installer_card_transfer_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->enum('transfer_type', ['change', 'lost']);
            $table->string('old_installer_card_card_number');
            $table->string('new_installer_card_card_number');
            $table->integer('transferred_points');
            $table->decimal('transferred_amount', 10, 2);
            $table->integer('transferred_credit_points');
            $table->decimal('transferred_credit_amount', 10, 2);
            $table->uuid('user_uuid');
            // $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('installer_card_transfer_logs');
    }
}
