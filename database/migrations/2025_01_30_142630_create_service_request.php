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
        if (!Schema::hasTable('service_requests')) {
            Schema::create('service_requests', function (Blueprint $table) {
                $table->id(); // Auto-increment primary key
                $table->string('ticket_id')->nullable(); // Declare before the foreign key
                $table->foreign('ticket_id')->references('control_no')->on('tickets')->onDelete('cascade');

                $table->string('form_no')->unique();
                $table->enum('service_type', ['walk_in', 'pull_out']);
                $table->string('name'); 
                $table->unsignedBigInteger('employee_id'); // Make sure it matches User model
                $table->string('department');
                $table->enum('condition', ['working', 'not-working', 'needs-repair']);
                $table->enum('status', ['in-repairs', 'repaired']);

                $table->integer('technical_support_id')->nullable();
                $table->foreign('technical_support_id')
                    ->references('employee_id') // Ensure employee_id in users is also an unsignedBigInteger
                    ->on('users');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
