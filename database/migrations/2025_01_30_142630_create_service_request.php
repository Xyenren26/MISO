<?php

// database/migrations/xxxx_xx_xx_create_service_requests_table.php

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
        Schema::create('service_requests', function (Blueprint $table) {
            $table->string('form_no')->primary(); // Form number as primary key
            $table->enum('service_type', ['walk_in', 'pull_out']);
            $table->string('department');
            $table->enum('condition', ['working', 'not-working', 'needs-repair']);
            $table->enum('status', ['in-repairs', 'repaired']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_requests');
    }
};
