<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('members', function (Blueprint $table) {            
            $table->foreignId('user_id');
            $table->string('member_type', 11)->nullable();                    
            $table->foreignId('member_id'); 
            $table->primary(['user_id', 'member_id']);
            $table->timestamps();
        });

        // Schema::create('members', function (Blueprint $table) {            
        //     $table->foreign('member_type')->references('user_type')->on('users');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
