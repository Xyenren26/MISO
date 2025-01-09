<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_managements', function (Blueprint $table) {
            $table->id('control_no'); // Primary key
            $table->string('name');
            $table->string('device');
            $table->enum('status', ['in-repairs', 'repaired', 'new-device-deployment', 'technical-report'])->default('in-repairs');
            $table->unsignedBigInteger('technical_support_id');
            $table->date('date');
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_managements');
    }
}
