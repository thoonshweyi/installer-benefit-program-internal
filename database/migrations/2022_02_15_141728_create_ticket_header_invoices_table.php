<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketHeaderInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_header_invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('ticket_header_uuid');
            $table->unsignedBigInteger('invoice_id');
            $table->string('invoice_no');
            $table->integer('valid_amount');
            $table->integer('valid_ticket_qty');
            $table->uuid('promotion_uuid');
            $table->foreign('ticket_header_uuid')->references('uuid')->on('ticket_headers')->onDelete('cascade');
            $table->foreign('promotion_uuid')->references('uuid')->on('promotions')->onDelete('cascade');
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
        Schema::dropIfExists('ticket_header_invoices');
    }
}
