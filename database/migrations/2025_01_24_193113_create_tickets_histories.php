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
        if (!Schema::hasTable('ticket_histories')) {
            Schema::create('ticket_histories', function (Blueprint $table) {
            $table->engine = 'InnoDB';  // Ensure InnoDB is used for foreign key support
            $table->string('ticket_id');  // Ticket ID (foreign key to tickets table)
            $table->integer('previous_technical_support')->nullable();  // Previous technical support ID (nullable)
            $table->integer('new_technical_support')->nullable();  // New technical support ID (nullable)
            $table->timestamp('changed_at');  // Timestamp of when the change occurred
            $table->timestamps();  // Laravel's created_at and updated_at timestamps

            // Foreign key constraints with cascading deletes and setting null on delete for technical supports
            $table->foreign('ticket_id')
                ->references('control_no')->on('tickets')
                ->onDelete('cascade');  // When a ticket is deleted, delete the history

            $table->foreign('previous_technical_support')
                ->references('employee_id')->on('users')
                ->onDelete('set null');  // When a user is deleted, set the previous support to null

            $table->foreign('new_technical_support')
                ->references('employee_id')->on('users')
                ->onDelete('set null');  // When a user is deleted, set the new support to null

            // Indexes for better performance on foreign key columns
            $table->index('ticket_id');
            $table->index('previous_technical_support');
            $table->index('new_technical_support');
        });
    }}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_histories');  // Drop the ticket_histories table
    }
};
