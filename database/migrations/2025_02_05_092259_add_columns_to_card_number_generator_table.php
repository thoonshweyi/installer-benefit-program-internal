<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCardNumberGeneratorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_number_generators', function (Blueprint $table) {
            $table->uuid('exported_by')->nullable();
            $table->dateTime("exported_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_number_generators', function (Blueprint $table) {
            $table->dropColumn(['exported_by','exported_date']);
        });
    }
}
