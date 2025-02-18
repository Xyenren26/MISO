<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('technical_reports')) {
        Schema::create('technical_reports', function (Blueprint $table) {
            $table->id();
            $table->string('control_no'); // Reference to tickets table
            $table->foreign('control_no')->references('control_no')->on('tickets')->onDelete('cascade');
            $table->timestamp('date_time');
            $table->string('department');
            $table->string('enduser');
            $table->text('specification');
            $table->text('problem');
            $table->text('workdone');
            $table->text('findings');
            $table->text('recommendation');
            $table->string('reported_by');
            $table->timestamp('reported_date')->nullable();
            $table->string('inspected_by');
            $table->timestamp('inspected_date')->nullable();
            $table->string('noted_by');
            $table->timestamp('noted_date')->nullable();
            $table->timestamps();
        });
    }}

    public function down(): void
    {
        Schema::dropIfExists('technical_reports');
    }
};
