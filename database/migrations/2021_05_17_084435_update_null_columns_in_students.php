<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNullColumnsInStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('nationality', 50)->nullable()->change();
            $table->string('religion', 25)->nullable()->change();
            $table->string('contact', 25)->nullable()->change();    
            $table->string('father_name')->nullable()->change();
            $table->string('father_contact', 25)->nullable()->change();
            $table->string('father_occupation', 50)->nullable()->change();
            $table->string('mother_name')->nullable()->change();
            $table->string('mother_contact', 25)->nullable()->change();
            $table->string('mother_occupation', 50)->nullable()->change();
            $table->integer('elementary_year')->nullable()->change();            
            $table->string('junior_high')->nullable()->change();            
            $table->integer('junior_high_year')->nullable()->change();            
            $table->string('senior_high')->nullable()->change();            
            $table->integer('senior_high_year')->nullable()->change();            
            $table->string('last_school')->nullable()->change();            
            $table->integer('last_school_year')->nullable()->change();      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
}
