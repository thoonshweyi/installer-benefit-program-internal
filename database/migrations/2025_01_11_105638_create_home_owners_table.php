<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_owners', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->tinyInteger('branch_id');

            $table->string("fullname");
            $table->string("phone");
            $table->string("address");
            $table->string("gender",10);
            $table->date("dob");
            $table->string("nrc");
            $table->string("passport")->nullable();
            $table->string('identification_card')->nullable();
            $table->boolean('member_active');
            $table->boolean('customer_active');
            $table->bigInteger("customer_rank_id");
            $table->string('customer_barcode');

            $table->string("titlename");
            $table->string("firstname");
            $table->string("lastnanme")->nullable();
            $table->bigInteger("province_id");
            $table->bigInteger("amphur_id");
            $table->string("nrc_no");
            $table->string("nrc_name");
            $table->string("nrc_short");
            $table->string("nrc_number");
            $table->bigInteger("gbh_customer_id");


            $table->uuid('user_uuid');

            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_owners');
    }
}
