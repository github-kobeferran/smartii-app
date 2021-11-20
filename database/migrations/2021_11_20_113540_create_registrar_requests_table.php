<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrarRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrar_requests', function (Blueprint $table) {
            $table->id();
            $table->string('type', 12);
            $table->integer('type_id');
            $table->string('requestor_type', 12);
            $table->integer('requestor_id');
            $table->boolean('status')->default(0); //0 = pending, 1 = approved, 2 = rejected
            $table->integer('marked_by')->nullable();
            $table->string('reject_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registrar_requests');
    }
}
