<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id(); // Primary key for the event
                $table->integer('employee_id'); // Foreign key referencing users.employee_id
                $table->string('title'); // Event title
                $table->dateTime('start'); // Event start date and time
                $table->dateTime('end')->nullable(); // Event end date and time (optional)
                $table->boolean('all_day')->default(false); // Whether the event is all-day
                $table->timestamps(); // Created at and updated at timestamps

                // Foreign key constraint
                $table->foreign('employee_id')
                      ->references('employee_id')
                      ->on('users')
                      ->onDelete('cascade'); // Cascade deletes if the user is deleted
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};