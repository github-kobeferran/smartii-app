<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClassIdToNullableInSubjectsTaken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects_taken', function (Blueprint $table) {
            $table->dropForeign('class_id');                              
        });
        
        Schema::table('subjects_taken', function (Blueprint $table) {            
            $table->foreign('class_id')->references('id')->on('classes')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects_taken', function (Blueprint $table) {
            //
        });
    }
}
