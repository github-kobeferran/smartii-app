<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->year('from_year')->nullable();
            $table->year('to_year')->nullable();
            $table->boolean('semester');
            $table->float('shs_price_per_unit', 8, 2)->default(0);
            $table->float('college_price_per_unit', 8, 2)->default(300);
            $table->timestamps();

        }); 
        
        Schema::table('balances', function (Blueprint $table) {
            $table->float('amount', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
