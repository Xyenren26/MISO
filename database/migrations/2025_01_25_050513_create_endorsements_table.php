<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndorsementsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('endorsements')) {
        Schema::create('endorsements', function (Blueprint $table) {
            $table->string('control_no')->primary();  // control_no as primary key
            $table->string('ticket_id')->nullable();
            $table->string('department')->nullable();
            $table->string('network')->nullable();
            $table->string('network_details')->nullable();
            $table->string('user_account')->nullable();
            $table->string('user_account_details')->nullable();
            $table->string('endorsed_to')->nullable();
            $table->date('endorsed_to_date')->nullable();  // Date type for endorsed_to_date
            $table->time('endorsed_to_time')->nullable();  // Time type for endorsed_to_time
            $table->string('endorsed_to_remarks')->nullable();
            $table->string('endorsed_by')->nullable();
            $table->date('endorsed_by_date')->nullable();  // Date type for endorsed_by_date
            $table->time('endorsed_by_time')->nullable();  // Time type for endorsed_by_time
            $table->string('endorsed_by_remarks')->nullable();  // Time type for endorsed_by_time
            $table->timestamps();  // created_at and updated_at timestamps
            $table->foreign('ticket_id')->references('control_no')->on('tickets')->onDelete('cascade');
        });
    }
}

    public function down()
    {
        Schema::dropIfExists('endorsements');
    }
}

