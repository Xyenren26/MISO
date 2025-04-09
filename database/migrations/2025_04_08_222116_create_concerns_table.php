<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('concerns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // 'main' or 'sub'
            $table->unsignedBigInteger('parent_id')->nullable(); // For sub-concerns
            $table->string('default_priority'); // urgent, high, medium, low
            $table->timestamps();
            $table->integer('assigned_user_id')->nullable();
            $table->boolean('assign_to_all_tech')->default(true);
            $table->foreign('assigned_user_id')->references('employee_id')->on('users');
            $table->foreign('parent_id')->references('id')->on('concerns');
        });

        // Insert main concerns
        DB::table('concerns')->insert([
            [
                'name' => 'Hardware Issue',
                'type' => 'main',
                'parent_id' => null,
                'default_priority' => 'high',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Software Issue',
                'type' => 'main',
                'parent_id' => null,
                'default_priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'File Transfer',
                'type' => 'main',
                'parent_id' => null,
                'default_priority' => 'low',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Network Connectivity',
                'type' => 'main',
                'parent_id' => null,
                'default_priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Get the IDs of the main concerns
        $hardwareId = DB::table('concerns')->where('name', 'Hardware Issue')->first()->id;
        $softwareId = DB::table('concerns')->where('name', 'Software Issue')->first()->id;
        $fileTransferId = DB::table('concerns')->where('name', 'File Transfer')->first()->id;
        $networkId = DB::table('concerns')->where('name', 'Network Connectivity')->first()->id;

        // Insert sub-concerns for Hardware Issue
        DB::table('concerns')->insert([
            [
                'name' => 'Broken Screen',
                'type' => 'sub',
                'parent_id' => $hardwareId,
                'default_priority' => 'urgent',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Battery Issue',
                'type' => 'sub',
                'parent_id' => $hardwareId,
                'default_priority' => 'high',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Keyboard Malfunction',
                'type' => 'sub',
                'parent_id' => $hardwareId,
                'default_priority' => 'high',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Printer Not Working',
                'type' => 'sub',
                'parent_id' => $hardwareId,
                'default_priority' => 'high',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mouse Not Responding',
                'type' => 'sub',
                'parent_id' => $hardwareId,
                'default_priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Power Supply Failure',
                'type' => 'sub',
                'parent_id' => $hardwareId,
                'default_priority' => 'high',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Other',
                'type' => 'sub',
                'parent_id' => $hardwareId,
                'default_priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert sub-concerns for Software Issue
        DB::table('concerns')->insert([
            [
                'name' => 'System Crash',
                'type' => 'sub',
                'parent_id' => $softwareId,
                'default_priority' => 'low',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Application Not Responding',
                'type' => 'sub',
                'parent_id' => $softwareId,
                'default_priority' => 'low',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'License Expired',
                'type' => 'sub',
                'parent_id' => $softwareId,
                'default_priority' => 'low',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Operating System Error',
                'type' => 'sub',
                'parent_id' => $softwareId,
                'default_priority' => 'high',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Software Installation Failure',
                'type' => 'sub',
                'parent_id' => $softwareId,
                'default_priority' => 'low',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Virus/Malware Infection',
                'type' => 'sub',
                'parent_id' => $softwareId,
                'default_priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Other',
                'type' => 'sub',
                'parent_id' => $softwareId,
                'default_priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert sub-concerns for File Transfer
        DB::table('concerns')->insert([
            [
                'name' => 'Slow Transfer',
                'type' => 'sub',
                'parent_id' => $fileTransferId,
                'default_priority' => 'low',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'File Corruption',
                'type' => 'sub',
                'parent_id' => $fileTransferId,
                'default_priority' => 'low',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Permission Denied',
                'type' => 'sub',
                'parent_id' => $fileTransferId,
                'default_priority' => 'low',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Other',
                'type' => 'sub',
                'parent_id' => $fileTransferId,
                'default_priority' => 'low',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert sub-concerns for Network Connectivity
        DB::table('concerns')->insert([
            [
                'name' => 'No Internet',
                'type' => 'sub',
                'parent_id' => $networkId,
                'default_priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Slow Connection',
                'type' => 'sub',
                'parent_id' => $networkId,
                'default_priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Frequent Disconnections',
                'type' => 'sub',
                'parent_id' => $networkId,
                'default_priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Other',
                'type' => 'sub',
                'parent_id' => $networkId,
                'default_priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concerns');
    }
};