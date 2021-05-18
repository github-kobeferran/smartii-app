<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SettingColumnsToNullableInStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('civil_status')->nullable()->change();
            $table->string('guardian_name')->nullable()->change();
            $table->string('guardian_contact')->nullable()->change();
            $table->string('guardian_occupation')->nullable()->change();
            $table->string('emergency_person_name')->nullable()->change();
            $table->string('emergency_person_contact')->nullable()->change();
            $table->string('emergency_person_address')->nullable()->change();           
            $table->string('gender')->nullable()->change();           
            $table->renameColumn('cur_status', 'student_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nullable_in_students', function (Blueprint $table) {
            //
        });
    }
}
