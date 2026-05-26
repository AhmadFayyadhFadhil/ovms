<?php

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
        // 1. Add missing columns to users table (driver workflow fields)
        Schema::table('users', function (Blueprint $table) {
            $table->string('department_id')->nullable()->after('name');
            $table->string('availability_status')->default('available')->after('department_id');
        });

        // 2. Update requests table with driver_id and trip-tracking columns
        Schema::table('requests', function (Blueprint $table) {
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null')->after('vehicle_id');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null')->after('driver_id');
            $table->timestamp('assigned_at')->nullable()->after('assigned_by');
            $table->timestamp('started_at')->nullable()->after('assigned_at');
            $table->timestamp('completed_at')->nullable()->after('started_at');
            $table->text('rejected_reason')->nullable()->after('completed_at');
            $table->string('driver_response_status')->nullable()->after('rejected_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['assigned_by']);
            $table->dropColumn([
                'driver_id',
                'assigned_by',
                'assigned_at',
                'started_at',
                'completed_at',
                'rejected_reason',
                'driver_response_status',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['department_id', 'availability_status']);
        });
    }
};
