<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignToClasses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms');

            $table->string('day', 25);
            $table->foreign('day')->references('name')->on('days');

            $table->unsignedBigInteger('faculty_id');
            $table->foreign('faculty_id')->references('id')->on('faculty');
        });
        Schema::table('subjects_taken', function (Blueprint $table) {

            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classes');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classes', function (Blueprint $table) {
            //
        });
    }
}
