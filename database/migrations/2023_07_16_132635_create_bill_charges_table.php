<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId("bill_id")->nullable()->constrained('bills')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId("plot_charge_id")->nullable()->constrained('plot_charges')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId("from_month")->nullable()->constrained('months')->onDelete('cascade')->onUpdate('cascade');
            $table->year('from_year')->nullable();
            $table->foreignId("to_month")->nullable()->constrained('months')->onDelete('cascade')->onUpdate('cascade');
            $table->year('to_year')->nullable();
            $table->integer('total_months')->nullable();
            $table->integer('no_of_transfer')->nullable();
            $table->integer('total_violation');
            $table->integer('amount')->nullable();
            $table->integer('total');
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
        Schema::dropIfExists('bill_charges');
    }
}
