<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the table doesn't exist before creating it
        if (!Schema::hasTable('service_requests')) {
            Schema::create('service_requests', function (Blueprint $table) {
                $table->id(); // Auto-increment primary key
                $table->string('form_no')->unique(); // Form number as unique identifier
                $table->enum('service_type', ['walk_in', 'pull_out']);
                $table->string('name'); // Employee name
                $table->integer('employee_id');
                $table->string('department');
                $table->enum('condition', ['working', 'not-working', 'needs-repair']);
                $table->enum('status', ['in-repairs', 'repaired']);
                $table->integer('technical_support_id')->nullable(); // Technical support ID as integer
                $table->foreign('technical_support_id') // Foreign key constraint for technical support ID
                    ->references('employee_id') // Reference the employee_id in the users table
                    ->on('users');
                $table->timestamps(); // Created_at & updated_at timestamps
            });
        }
    }
    

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
