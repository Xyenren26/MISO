<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndorsementsTable extends Migration
{
    public function up()
    {
        Schema::create('endorsements', function (Blueprint $table) {
            $table->string('control_no')->primary();  // control_no as primary key
            $table->string('department');
            $table->string('network');
            $table->string('network_details');
            $table->string('user_account');
            $table->string('user_account_details');
            $table->string('endorsed_to');
            $table->date('endorsed_to_date');  // Date type for endorsed_to_date
            $table->time('endorsed_to_time');  // Time type for endorsed_to_time
            $table->string('endorsed_to_remarks');
            $table->string('endorsed_by');
            $table->date('endorsed_by_date');  // Date type for endorsed_by_date
            $table->time('endorsed_by_time');  // Time type for endorsed_by_time
            $table->string('endorsed_by_remarks');  // Time type for endorsed_by_time
            $table->timestamps();  // created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('endorsements');
    }
}

