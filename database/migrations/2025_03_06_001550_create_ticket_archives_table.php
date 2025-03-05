<?php

// database/migrations/2025_03_06_000001_create_ticket_archives_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketArchivesTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_archives', function (Blueprint $table) {
            $table->string('control_no')->primary();
            $table->unsignedBigInteger('employee_id');
            $table->string('name');
            $table->string('department');
            $table->string('priority');
            $table->text('concern')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('technical_support_id')->nullable();
            $table->string('technical_support_name')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('time_in')->nullable();
            $table->timestamp('time_out')->nullable();
            $table->timestamps();
            $table->timestamp('archived_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_archives');
    }
}
