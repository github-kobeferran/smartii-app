<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MajorDbUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('contact');
        });

        //add last_school_year
        Schema::table('applicants', function (Blueprint $table) {
            $table->unsignedSmallInteger('last_school_year');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('father_contact');
            $table->dropColumn('father_occupation');
            $table->dropColumn('mother_contact');
            $table->dropColumn('mother_occupation');            
            $table->dropColumn('guardian_contact');
            $table->dropColumn('guardian_occupation');            
            $table->dropColumn('emergency_person_name');            
            $table->dropColumn('emergency_person_address');            
            $table->dropColumn('elementary');            
            $table->dropColumn('elementary_year');            
            $table->dropColumn('junior_high');            
            $table->dropColumn('junior_high_year');            
            $table->dropColumn('senior_high');            
            $table->dropColumn('senior_high_year');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('address', 191);
            $table->string('contact', 25);
        });

        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('last_school_year');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('father_contact', 25);
            $table->string('father_occupation', 50);
            $table->string('mother_contact',25);
            $table->string('mother_occupation', 50);            
            $table->string('guardian_contact');
            $table->string('guardian_occupation');            
            $table->string('emergency_person_name');            
            $table->string('emergency_person_address');            
            $table->string('elementary');            
            $table->integer('elementary_year');            
            $table->string('junior_high');            
            $table->integer('junior_high_year');            
            $table->string('senior_high');            
            $table->integer('senior_high_year');            
        });
    }
}
