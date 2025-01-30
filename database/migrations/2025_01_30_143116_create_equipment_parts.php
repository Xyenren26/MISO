<?php

// database/migrations/xxxx_xx_xx_create_equipment_parts_table.php

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
        Schema::create('equipment_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_description_id');
            $table->string('part_name');
            $table->boolean('is_present')->default(false); // Add this line
            $table->timestamps();
        
            $table->foreign('equipment_description_id')->references('id')->on('equipment_descriptions');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('equipment_parts');
    }
};
