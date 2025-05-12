<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bill_number')->unique();
            $table->foreignId("allotee_id")->nullable()->constrained('allotees')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId("bank_id")->nullable()->constrained('banks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId("sector_id")->nullable()->constrained('sectors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId("size_id")->nullable()->constrained('sizes')->onDelete('cascade')->onUpdate('cascade');
            $table->year('year')->nullable();
            $table->foreignId("from_month")->nullable()->constrained('months')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId("to_month")->nullable()->constrained('months')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('total_months')->nullable()->default(0);
            $table->date('issue_date');
            $table->date('due_date');
            $table->tinyInteger('is_paid');
            $table->foreignId("generated_by")->nullable()->constrained('users')->onDelete('set null')->onUpdate('set null');
            $table->tinyInteger('is_period');
            $table->integer('bill_total');
            $table->integer('arrears');
            $table->integer('total');
            $table->integer('sub_charges');
            $table->integer('sub_total');
            $table->integer('due_amount');
            $table->boolean('is_time_period')->default(false);
            $table->boolean('is_non_time_period')->default(false);
            $table->boolean('is_violation')->default(false);
            $table->boolean('is_non_user')->default(false);
            $table->boolean('is_stamp_duty')->default(false);
             $table->timestamps();
            $table->tinyInteger('is_active')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
