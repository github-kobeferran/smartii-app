<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeColumnsNullableInFaculty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faculty', function (Blueprint $table) {
            $table->string('faculty_id')->nullable()->change();
            $table->string('middle_name')->nullable()->change();
            $table->string('middle_name')->nullable()->change();
            $table->string('civil_status')->nullable()->change();
            $table->string('religion')->nullable()->change();
            $table->string('contact')->nullable()->change();
            $table->string('college_alumni')->nullable()->change();
            $table->string('gender')->nullable()->change();
            $table->boolean('is_admin')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faculty', function (Blueprint $table) {
            //
        });
    }
}
