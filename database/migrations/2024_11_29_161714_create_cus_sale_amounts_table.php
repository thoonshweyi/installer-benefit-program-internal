<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCusSaleAmountsTable extends Migration
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
        Schema::create('cus_sale_amounts', function (Blueprint $table) {
            $table->id();
            $table->string('customer_barcode');
            $table->string("phone");
            $table->decimal('sale_amount', 19, 2);
            $table->uuid("sale_amount_check_uuid");
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
        Schema::dropIfExists('cus_sale_amounts');
    }
}
