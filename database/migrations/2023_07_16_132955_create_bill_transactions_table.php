<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("bill_id")->nullable()->constrained('bills')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('total');
            $table->tinyInteger('is_paid');
            $table->integer('paid_amount');
            $table->integer('due_amount');
            $table->date('payment_date')->nullable();
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
        Schema::dropIfExists('bill_transactions');
    }
}
