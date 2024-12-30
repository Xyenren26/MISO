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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('control_no'); // Auto-incrementing primary key
            $table->unsignedBigInteger('employee_id'); // Foreign key
            $table->string('name');
            $table->string('department');
            $table->enum('priority', ['urgent', 'semi-urgent', 'non-urgent']);
            $table->string('device');
            $table->text('concern');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('technical_support_id')->nullable(); // Foreign key
            $table->string('technical_support_name')->nullable();
            $table->enum('status', ['open', 'closed', 'in-progress'])->default('open');
            $table->timestamp('time_in')->useCurrent(); // Default to current time
            $table->timestamp('time_out')->nullable(); // Nullable field
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
