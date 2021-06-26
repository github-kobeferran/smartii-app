<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects_subjects', function (Blueprint $table) {            
            $table->foreignId('subject_id')->references('id')->on('subjects');
            $table->foreignId('subject_pre_req_id')->references('id')->on('subjects');
            $table->primary(['subject_id', 'subject_pre_req_id']);
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
        Schema::dropIfExists('subjects_subjects');
    }
}
