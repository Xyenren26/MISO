<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Create the table if it doesn't exist
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('sender_id');
                $table->unsignedInteger('receiver_id');
                $table->text('message')->nullable();
                $table->string('file_path')->nullable(); // Add file path column
                $table->boolean('is_read')->default(false);
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('sender_id')->references('employee_id')->on('users')->onDelete('cascade');
                $table->foreign('receiver_id')->references('employee_id')->on('users')->onDelete('cascade');
            });
        } else {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('messages', 'is_read')) {
                Schema::table('messages', function (Blueprint $table) {
                    $table->boolean('is_read')->default(false);
                });
            }

            if (!Schema::hasColumn('messages', 'file_path')) {
                Schema::table('messages', function (Blueprint $table) {
                    $table->string('file_path')->nullable();
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('messages')) {
            Schema::dropIfExists('messages');
        }
    }
};
