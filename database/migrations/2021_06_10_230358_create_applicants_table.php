<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->boolean('dept');
            $table->unsignedBigInteger('program');
            $table->string('last_name', 100);                        
            $table->string('first_name', 100);                        
            $table->string('middle_name', 100); 
            $table->date('dob');                       
            $table->string('gender', 11);
            $table->string('present_address');
            $table->string('last_school');
            $table->string('id_pic');
            $table->string('birth_cert');
            $table->string('good_moral');
            $table->string('report_card');
            $table->string('resubmit_file', 4)->nullable();
            $table->boolean('approved')->default(0);
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
        Schema::dropIfExists('applicants');
    }
}
