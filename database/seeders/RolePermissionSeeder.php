<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            // Request permissions
            'create-request',
            'view-own-request',
            'view-all-requests',
            'update-request',
            'delete-request',
            'approve-request',
            'reject-request',
            
            // Vehicle permissions
            'view-vehicle',
            'create-vehicle',
            'update-vehicle',
            'delete-vehicle',
            
            // User permissions
            'view-user',
            'create-user',
            'update-user',
            'delete-user',
            
            // Audit log permissions
            'view-audit-log',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $admin = Role::create(['name' => 'Admin']);
        $ga = Role::create(['name' => 'GA']);
        $approver = Role::create(['name' => 'Approver']);
        $employee = Role::create(['name' => 'Employee']);
        $driver = Role::create(['name' => 'Driver']);

        // Assign permissions to roles
        // Admin has all permissions
        $admin->givePermissionTo(Permission::all());

        // GA can manage vehicles and view requests
        $ga->givePermissionTo([
            'view-vehicle',
            'create-vehicle',
            'update-vehicle',
            'delete-vehicle',
            'view-all-requests',
            'view-audit-log',
        ]);

        // Approver can approve/reject requests and view all requests
        $approver->givePermissionTo([
            'view-all-requests',
            'approve-request',
            'reject-request',
            'view-vehicle',
            'view-audit-log',
        ]);

        // Employee can create and view own requests
        $employee->givePermissionTo([
            'create-request',
            'view-own-request',
            'view-vehicle',
        ]);

        // Driver can view vehicles and own requests
        $driver->givePermissionTo([
            'view-vehicle',
            'view-own-request',
        ]);
    }
}
