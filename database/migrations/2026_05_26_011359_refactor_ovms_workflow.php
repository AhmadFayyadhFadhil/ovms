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
        // 1. Update requests table
        Schema::table('requests', function (Blueprint $table) {
            $table->string('department_id')->nullable()->after('user_id'); // If using simple string or relate to a departments table (not defined yet, let's keep string/nullable)
            $table->string('destination_city')->nullable()->after('purpose');
            $table->string('destination_place')->nullable()->after('destination_city');
            $table->integer('passenger_count')->default(1)->after('destination_place');
            $table->string('priority')->default('normal')->after('passenger_count');
            
            // Drop old approval columns as they are moved to request_approvals
            $table->dropForeign(['approver_id']);
            $table->dropColumn(['approver_id', 'approval_date']);
        });

        // Alter status column to string to use PHP 8 Enums freely
        Schema::table('requests', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
            $table->dateTime('end_time')->nullable()->change();
        });

        // 2. Create request_approvals table
        Schema::create('request_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->string('role'); // e.g. dept_head, hrd_head
            $table->string('status'); // e.g. approved, rejected
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Update assignments table -> rename to driver_assignments, drop vehicle_id (moved to trip)
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropIndex(['vehicle_id', 'assigned_at']); // Drop the old index
            $table->dropColumn('vehicle_id');
            
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null')->after('driver_id');
            $table->text('reject_reason')->nullable()->after('notes');
            
            $table->string('status')->default('pending_driver')->change();
        });

        // 4. Create operational_trips table
        Schema::create('operational_trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('restrict');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime')->nullable();
            $table->string('status')->default('scheduled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operational_trips');
        Schema::dropIfExists('request_approvals');

        Schema::table('assignments', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('restrict');
            $table->dropForeign(['assigned_by']);
            $table->dropColumn(['assigned_by', 'reject_reason']);
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('approval_date')->nullable();
            $table->dropColumn(['department_id', 'destination_city', 'destination_place', 'passenger_count', 'priority']);
        });
    }
};
