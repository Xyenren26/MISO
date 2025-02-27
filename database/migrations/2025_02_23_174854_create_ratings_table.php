<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        if (!Schema::hasTable('ratings')) {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->string('control_no')->index();
            $table->integer('technical_support_id')->index();
            $table->integer('rating');
            $table->timestamps();

            // Foreign keys
            $table->foreign('control_no')->references('control_no')->on('tickets')->onDelete('cascade');
            $table->foreign('technical_support_id')->references('employee_id')->on('users')->onDelete('cascade');
        });
    }}

    public function down() {
        Schema::dropIfExists('ratings');
    }
};
