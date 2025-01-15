<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleAmountChecksTable extends Migration
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
        Schema::create('sale_amount_checks', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid")->unique();
            $table->string('primary_phone');
            $table->decimal('total_sale_amount', 19, 2);
            $table->tinyInteger('branch_id');
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
        Schema::dropIfExists('sale_amount_checks');
    }
}
