<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSubjectsTaken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects_taken', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->change();                                    
            $table->unsignedBigInteger('subject_id')->change();
            $table->unsignedBigInteger('from_year')->change();
            $table->unsignedBigInteger('semester')->change();
            
            
        });

        Schema::table('subjects_taken', function (Blueprint $table) {
           $table->id();                        
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
