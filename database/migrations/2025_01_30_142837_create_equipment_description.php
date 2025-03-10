<?php

// database/migrations/xxxx_xx_xx_create_equipment_descriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('equipment_descriptions')) {
        Schema::create('equipment_descriptions', function (Blueprint $table) {
            $table->id();
            $table->string('form_no'); // Reference to the service request form number
            $table->string('equipment_type');
            $table->string('brand')->nullable();
            $table->string('device')->nullable();
            $table->string('description')->nullable();
            $table->text('remarks')->nullable();
            $table->foreign('form_no')->references('form_no')->on('service_requests')->onDelete('cascade');
            $table->timestamps();
        });
    }
}

    public function down()
    {
        Schema::dropIfExists('equipment_descriptions');
    }
};
