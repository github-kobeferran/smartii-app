<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropStPrimaryKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('subjects_taken', function(Blueprint $table){            
            $table->unsignedInteger('id')->change();
            $table->dropColumn('id');                   
        });

        Schema::table('subjects_taken', function (Blueprint $table) {                        
            $table->year('from_year');
            $table->year('to_year');
            $table->boolean('semester');
            // $table->foreign('class_id')->references('id')->on('classes');
            $table->dropTimestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
