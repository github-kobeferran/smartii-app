<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name',25);
            $table->boolean('enable');
        });

        Schema::create('days', function (Blueprint $table) {            
            $table->string('name', 25)->unique();
            $table->boolean('enable');
        });
        
        Schema::create('classes', function (Blueprint $table) {
            $table->id();            
            $table->time('time', $precision = 0);            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
        Schema::dropIfExists('days');
        Schema::dropIfExists('rooms');
    }
}
