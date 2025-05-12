<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargesTypesTable extends Migration
{
    public function up()
    {
        Schema::create('charge_types', function (Blueprint $table) {

            $table->id();
            $table->string('name', 191);
            $table->timestamps();
            $table->softDeletes();
            $table->tinyInteger('is_active')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('charge_types');
    }
}
