<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('titlename')->nullable();
            $table->string('firstname');
            $table->string('lastname')->nullable();
            $table->string('phone_no');
            $table->string('phone_no_2')->nullable();
            $table->string('email')->nullable();
            $table->string('passport')->nullable();
            $table->integer('nrc_no')->nullable();
            $table->integer('nrc_name')->nullable();
            $table->integer('nrc_short')->nullable();
            $table->integer('nrc_number')->nullable();
            $table->integer('national_id')->nullable();
            $table->integer('amphur_id')->nullable();;
            $table->integer('province_id')->nullable();
            $table->string('customer_type')->nullable();
            $table->string('member_no')->nullable();
            $table->string('customer_no')->nullable();
            $table->string('address')->nullable();
            $table->string('address',100)->nullable();
            $table->boolean('foreigner')->default(false);
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
        Schema::dropIfExists('customers');
    }
}
