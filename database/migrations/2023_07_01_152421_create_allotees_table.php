<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlloteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allotees', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('plot_no')->default(0);
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('account_no')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->text('address')->nullable();
            $table->foreignId("sector_id")->nullable()->constrained('sectors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId("size_id")->nullable()->constrained('sizes')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('is_active')->default('1');
            $table->string('arrears')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allotees');
    }
}
