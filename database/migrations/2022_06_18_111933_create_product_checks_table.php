<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_checks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('sub_promotion_uuid');
            $table->string('check_product_code');
            $table->string('check_product_name');
            $table->integer('check_product_qty');
            $table->integer('check_product_amount');
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
        Schema::dropIfExists('product_checks');
    }
}
