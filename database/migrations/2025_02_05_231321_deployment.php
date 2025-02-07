<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->string('control_number')->unique();
            $table->text('purpose');
            $table->enum('status', ['new', 'used']);
            $table->json('components')->nullable(); // Stores selected components
            $table->json('software')->nullable(); // Stores selected software
            $table->string('brand_name')->nullable();
            $table->text('specification')->nullable();
            $table->string('received_by');
            $table->date('received_date')->nullable();
            $table->string('issued_by');
            $table->date('issued_date')->nullable();
            $table->string('noted_by');
            $table->date('noted_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deployments');
    }
};
