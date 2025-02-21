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
        if (!Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table) {
            $table->string('control_no')->primary(); // control_no as the primary key (if not auto-incrementing)
            $table->integer('employee_id')->nullable(); // Foreign key to users table (general employee)

            // pinalitan ko yung technical - rogelio
            $table->integer('technical_support_id')->nullable(false)->default(0); // Ensures a default value  // Foreign key to users table (specific to technical-support role)
            $table->string('name');
            $table->string('department');
            $table->enum('priority', ['urgent', 'semi-urgent', 'non-urgent']);
            $table->text('concern');
            $table->text('remarks')->nullable();
            $table->string('technical_support_name')->nullable(); // Name of technical support (just a string)
            $table->enum('status', ['endorsed', 'completed', 'in-progress', 'technical-report', 'pull-out', 'deployment'])->default('in-progress');
            $table->timestamp('time_in')->useCurrent(); // Default to current time
            $table->timestamp('time_out')->nullable(); // Nullable field
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign Key Constraints (both referencing users table)
            $table->foreign('employee_id')->references('employee_id')->on('users')->onDelete('cascade');
            $table->foreign('technical_support_id')->references('employee_id')->on('users')->onDelete('cascade');
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
