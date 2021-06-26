<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGender extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // $table->renameColumn('gender', 'sex');
            $table->string('gender', 11);
        });
        Schema::table('faculty', function (Blueprint $table) {
            // $table->renameColumn('gender', 'sex');
            $table->string('gender', 11);
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
