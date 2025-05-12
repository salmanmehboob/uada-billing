<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthsTable extends Migration
{
    public function up()
    {
        Schema::create('months', function (Blueprint $table) {

            $table->id();
            $table->string('name', 191);
            $table->string('short', 191);
            $table->timestamps();
            $table->softDeletes();
            $table->tinyInteger('is_active')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('months');
    }
}
