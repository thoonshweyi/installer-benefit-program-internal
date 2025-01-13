<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_headers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('ticket_header_no');
            $table->uuid('promotion_uuid');
            $table->uuid('customer_uuid');
            $table->unsignedBigInteger('branch_id');
            $table->integer('total_valid_amount');
            $table->integer('total_valid_ticket_qty');
            $table->tinyInteger('reprint')->nullable();
            $table->tinyInteger('ticket_type');
            $table->tinyInteger('status');
            $table->uuid('created_by');
            $table->uuid('printed_by')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->uuid('canceled_by')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->foreign('customer_uuid')->references('uuid')->on('customers')->onDelete('cascade');
            $table->foreign('created_by')->references('uuid')->on('users')->onDelete('cascade');
            $table->foreign('printed_by')->references('uuid')->on('users')->onDelete('cascade');
            $table->foreign('canceled_by')->references('uuid')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('ticket_headers');
    }
}
