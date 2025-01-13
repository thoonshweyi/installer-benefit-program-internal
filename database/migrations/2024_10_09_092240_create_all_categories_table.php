<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllCategoriesTable extends Migration
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
        Schema::create('all_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('maincatid');
            $table->string('remark');
            $table->integer('product_category_id');
            $table->string('product_category_code');
            $table->string('product_category_name');
            $table->integer('product_group_id');
            $table->string('product_group_code');
            $table->string('product_group_name');
            $table->integer('product_pattern_id');
            $table->string('product_pattern_code');
            $table->string('product_pattern_name');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_categories');
    }
}
