<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnChecksTable extends Migration
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
        Schema::create('return_checks', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('branch_id');
            $table->string("invoice_number");
            $table->uuid("collection_transaction_uuid")->nullable();
            $table->enum('flag', ['found', 'not found']);
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
        Schema::dropIfExists('return_checks');
    }
}
