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
    if (!Schema::hasTable('approvals')) {
    Schema::create('approvals', function (Blueprint $table) {
        $table->id();
        $table->string('ticket_id')->nullable(); // For tickets
        $table->string('form_no')->nullable(); // For device deployments
        $table->string('name'); // Approver Name
        $table->dateTime('approve_date')->nullable();
        $table->string('noted_by')->nullable();
        $table->timestamps();
    });
}
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
