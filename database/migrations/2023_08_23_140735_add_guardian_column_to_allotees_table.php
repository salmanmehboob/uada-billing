<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuardianColumnToAlloteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('allotees', function (Blueprint $table) {
            $table->string('guardian_name')->nullable();
            $table->foreignId("type_id")->nullable()->constrained('types')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('allotees', function (Blueprint $table) {
            $table->dropColumn('guardian_name');
        });
    }
}
