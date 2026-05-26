<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $admin = User::factory()->create([
            'name'     => 'Administrator',
            'email'    => 'admin@ovms.test',
            'password' => 'password',
            'department_id' => null,
        ]);
        $admin->assignRole('Admin');

        $ga = User::factory()->create([
            'name'     => 'General Affairs',
            'email'    => 'ga@ovms.test',
            'password' => 'password',
            'department_id' => null,
        ]);
        $ga->assignRole('GA');

        $approver = User::factory()->create([
            'name'     => 'Manager Approver',
            'email'    => 'approver@ovms.test',
            'password' => 'password',
            'department_id' => 'IT',
            'rank'     => 'Manager',
        ]);
        $approver->assignRole('Approver');

        $employee = User::factory()->create([
            'name'     => 'Employee Test',
            'email'    => 'employee@ovms.test',
            'password' => 'password',
            'department_id' => 'IT',
        ]);
        $employee->assignRole('Employee');

        $driver = User::factory()->create([
            'name'     => 'Driver Test',
            'email'    => 'driver@ovms.test',
            'password' => 'password',
            'department_id' => null,
            'availability_status' => 'available',
        ]);
        $driver->assignRole('Driver');
    }
}
