<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProgramToFees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->unsignedSmallInteger('program_id')->nullable();
            $table->boolean('level')->nullable()->default(NULL)->change();
            $table->boolean('sem')->nullable()->default(NULL)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('fees', function (Blueprint $table) {
            $table->dropColumn('program_id');
            $table->boolean('level')->default(50)->change();
            $table->boolean('sem')->default(50)->change();
            $table->boolean('sem')->default(5)->change();
        });
       
    }
}
