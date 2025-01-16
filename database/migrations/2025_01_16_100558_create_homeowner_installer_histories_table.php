<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeownerInstallerHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeowner_installer_histories', function (Blueprint $table) {
            $table->id();
            $table->string("installer_card_card_number");
            $table->json("home_owner_uuids");
            $table->uuid("user_uuid");
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
        Schema::dropIfExists('homeowner_installer_histories');
    }
}
