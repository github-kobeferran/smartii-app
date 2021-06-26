<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesInInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      
        Schema::table('invoices', function (Blueprint $table) {            
            $table->unsignedBigInteger('balance_id')->change();
            $table->dropColumn('balance_id');
        });

        Schema::table('invoices', function (Blueprint $table) {            
            $table->string('invoice_id', 11);
            $table->string('student_id', 11);
            $table->string('admin_id', 11);
            $table->float('balance', 8, 2);
            $table->float('payment', 8, 2);
            $table->float('remaining_bal', 8, 2);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
}
