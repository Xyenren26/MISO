<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('employee_id')->nullable(); // Fixed the column definition
            $table->string('first_name')->nullable(); // First name
            $table->string('last_name')->nullable(); // Last name
            $table->string('email')->unique(); // Email
            $table->timestamp('email_verified_at')->nullable(); // Email verification timestamp
            $table->string('password'); // Password
            $table->string('department')->nullable(); // Department
            $table->string('phone_number')->nullable(); // Phone number
            $table->string('username')->unique(); // Username
            $table->enum('account_type', ['administrator', 'technical_support', 'end_user']); // Account type
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->rememberToken(); // Remember token
            $table->string('session_id')->nullable(); // Session ID
            $table->timestamps(); // Created at and updated at
            $table->timestamp('last_activity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
