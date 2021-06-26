<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 11)->unique();
            $table->string('last_name', 100);
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->date('dob');
            $table->boolean('gender');
            $table->string('nationality', 50);
            $table->string('civil_status', 25);
            $table->string('religion', 25);
            $table->string('email', 100)->unique();
            $table->string('contact', 25);            
            $table->string('level', 25); // freshman, sophomore || jhs, shs
            $table->integer('department'); // 0 => shs, 1 => college
            $table->foreignId('program_id'); //course, track  
            $table->string('permanent_address');
            $table->string('present_address');
            $table->string('father_name');
            $table->string('father_contact', 25);
            $table->string('father_occupation', 50);
            $table->string('mother_name');
            $table->string('mother_contact', 25);
            $table->string('mother_occupation', 50);
            $table->string('guardian_name');
            $table->string('guardian_contact', 25);
            $table->string('guardian_occupation', 50);
            $table->string('emergency_person_name');
            $table->string('emergency_person_contact', 25);
            $table->string('emergency_person_address');            
            $table->string('elementary');            
            $table->integer('elementary_year');            
            $table->string('junior_high');            
            $table->integer('junior_high_year');            
            $table->string('senior_high');            
            $table->integer('senior_high_year');            
            $table->string('last_school');            
            $table->integer('last_school_year');            
            $table->boolean('cur_status'); // 0 => regular, 1 => irregular
            $table->boolean('transferee'); // 0 => non-transferee, 1 => transferre
            $table->foreignId('section_id'); // section
            $table->boolean('created_by_admin'); // 0 => no, 1 => yes
            $table->foreignId('balance_id'); // section
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
