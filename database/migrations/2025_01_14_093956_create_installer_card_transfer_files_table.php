<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallerCardTransferFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installer_card_transfer_files', function (Blueprint $table) {
            $table->id();
            $table->uuid("installer_card_transfer_log_uuid");
            $table->string("image");
            $table->foreign('installer_card_transfer_log_uuid')->references('uuid')->on('installer_card_transfer_logs')->onDelete('cascade');
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
        Schema::dropIfExists('installer_card_transfer_files');
    }
}
