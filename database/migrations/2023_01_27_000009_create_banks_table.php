<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {

            $table->id();
            $table->string('name', 191);
            $table->string('branch', 191);
            $table->string('account_no', 191);
            $table->timestamps();
            $table->softDeletes();
            $table->tinyInteger('is_active')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
