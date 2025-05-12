<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlotChargesTable extends Migration
{
    public function up()
    {
        Schema::create('plot_charges', function (Blueprint $table) {

            $table->bigIncrements('id')->unsigned();
            $table->foreignId("size_id")->nullable()->constrained('sizes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId("charge_id")->nullable()->constrained('charges')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId("charge_type_id")->nullable()->constrained('charge_types')->onDelete('cascade')->onUpdate('cascade');
            $table->string('amount');
            $table->year('year');
            $table->tinyInteger('is_period')->default(1);
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('plot_charges');
    }
}
