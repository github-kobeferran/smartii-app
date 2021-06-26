<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculty', function (Blueprint $table) {
            $table->id();
            $table->string('faculty_id')->unique();
            $table->string('last_name', 100);
            $table->string('first_name', 100);
            $table->string('middle_name', 100);
            $table->date('dob');
            $table->boolean('gender');
            $table->string('civil_status', 25);        
            $table->string('religion', 25);
            $table->string('email', 100)->unique();
            $table->string('contact');
            $table->string('college_alumni');            
            $table->boolean('is_admin'); // 0 => no, 1 => yes
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
        Schema::dropIfExists('faculty');
    }
}
