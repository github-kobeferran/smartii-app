<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAccessGrantDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('access_grant')->default(0)->change();      
        });
    
    }

    /**
     * Reverse the http://smartii-app.test/email/verify/25/cb8cc233bc7f0b6719d30e407657ab86be7216d0?expires=1624356997&signature=049fdbe23319f6dee3033da1f61eeda085a2e88e3b5a48840f2c7138190df5b3migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
