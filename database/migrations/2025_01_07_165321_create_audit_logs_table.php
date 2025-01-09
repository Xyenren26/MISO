<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->timestamp('date_time'); // Date and time of the action
            $table->string('action_type'); // Action type (e.g., created, updated, endorsed)
            $table->unsignedBigInteger('performed_by'); // User ID of the person who performed the action
            $table->string('ticket_or_device_id')->nullable(); // Related Ticket or Device ID
            $table->text('remarks')->nullable(); // Additional remarks or details

            $table->timestamps(); // Laravel's created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}
