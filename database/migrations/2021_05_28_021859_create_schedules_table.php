<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        

        Schema::create('schedules', function (Blueprint $table) {

            $table->id();
            $table->time('time');
            $table->string('day', 3);     
            $table->foreignId('room_id')->constrained();
            
        });

        Schema::table('classes', function (Blueprint $table){

            $table->dropColumn('time');
            $table->dropColumn('day');            
            $table->unsignedInteger('room_id')->change();
            $table->dropColumn('room_id');       
            $table->foreignId('schedule_id')->constrained();

        });    
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
