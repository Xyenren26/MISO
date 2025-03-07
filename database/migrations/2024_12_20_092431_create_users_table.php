<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->integer('employee_id')->primary(); // Fixed the column definition
                $table->integer('id')->nullable();
                $table->string('first_name')->nullable(); // First name
                $table->string('last_name')->nullable(); // Last name
                $table->string('name')->nullable();
                $table->string('email')->unique(); // Email
                $table->timestamp('email_verified_at')->nullable(); // Email verification timestamp
                $table->string('password'); // Password
                $table->string('department')->nullable(); // Department
                $table->string('phone_number')->nullable(); // Phone number
                $table->enum('account_type', ['administrator', 'technical_support','technical_support_head' ,'end_user']); // Account type
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->rememberToken(); // Remember token
                $table->string('session_id')->nullable(); // Session ID
                $table->timestamps(); // Created at and updated at
                $table->timestamp('last_activity')->nullable();
                $table->boolean('is_first_login')->default(true);
            });
        }

        // Insert administrator account
        DB::table('users')->insert([
            'employee_id' => 0000001,
            'id' => 0000001,
            'first_name' => 'TechTrack',
            'last_name' => 'Admin',
            'name' => 'TechTrack Admin',
            'department' => 'Management Information Systems Office (MISO)',
            'email' => 'techtrackesmms@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('M@lupetako123'),
            'account_type' => 'administrator',
            'status' => 'active',
            'is_first_login' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
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
