<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('pdf_passwords')) {
            Schema::create('pdf_passwords', function (Blueprint $table) {
                $table->id();
                $table->integer('employee_id'); // Matches users.employee_id type
                $table->text('passwords'); // Encrypted JSON
                $table->string('document_name');
                $table->string('ip_address');
                $table->integer('access_count')->default(0);
                $table->timestamp('expires_at');
                $table->timestamps();
                
                // Foreign key constraint
                $table->foreign('employee_id')
                      ->references('employee_id')
                      ->on('users')
                      ->onDelete('cascade');
                      
                $table->index('expires_at');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pdf_passwords');
    }
};