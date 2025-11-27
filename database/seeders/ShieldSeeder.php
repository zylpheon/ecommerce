<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $resources = ['category', 'customer', 'product', 'order', 'user', 'permission', 'role'];
        $actions = ['view', 'view_any', 'create', 'update', 'delete', 'delete_any'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}_{$resource}",
                    'guard_name' => 'web'
                ]);
            }
        }

        // Create Roles
        $owner = Role::firstOrCreate(['name' => 'Owner', 'guard_name' => 'web']);
        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $staff = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);
        $guest = Role::firstOrCreate(['name' => 'Guest', 'guard_name' => 'web']);
        $superAdminName = config('filament-shield.super_admin.name', 'super_admin');
        $superAdmin = Role::firstOrCreate(['name' => $superAdminName, 'guard_name' => 'web']);

        // Assign Permissions to Roles
        // Owner - Full Access
        $owner->givePermissionTo(Permission::all());

        // Super Admin - Full Access
        $superAdmin->givePermissionTo(Permission::all());

        // Manager - Can manage most things except users and roles
        $managerPermissions = Permission::where(function ($query) {
            $query->where('name', 'like', '%category%')
                ->orWhere('name', 'like', '%product%')
                ->orWhere('name', 'like', '%order%')
                ->orWhere('name', 'like', '%customer%');
        })->get();
        $manager->givePermissionTo($managerPermissions);

        // Staff - Can view and create, limited update
        $staffPermissions = Permission::where(function ($query) {
            $query->where('name', 'like', 'view%')
                ->orWhere('name', 'like', 'create_order')
                ->orWhere('name', 'like', 'create_customer')
                ->orWhere('name', 'like', 'update_order')
                ->orWhere('name', 'like', 'update_customer');
        })->get();
        $staff->givePermissionTo($staffPermissions);

        // Guest - View only
        $guestPermissions = Permission::where('name', 'like', 'view%')->get();
        $guest->givePermissionTo($guestPermissions);

        // Create Users
        $users = [
            [
                'name' => 'Ridhwan',
                'email' => 'ridhwan@example.com',
                'role' => 'Owner'
            ],
            [
                'name' => 'Ahsan',
                'email' => 'ahsan@example.com',
                'role' => 'Manager'
            ],
            [
                'name' => 'Rizal',
                'email' => 'rizal@example.com',
                'role' => 'Staff'
            ],
            [
                'name' => 'Rapip',
                'email' => 'rapip@example.com',
                'role' => 'Guest'
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($userData['role']);
        }

        // Assign existing admin
        $existingAdmin = User::where('email', 'lrv94451@gmail.com')->first();
        if ($existingAdmin) {
            $existingAdmin->assignRole($superAdminName);
        }

        $this->command->info('✅ Roles, Permissions, and Users created successfully!');
        $this->command->info('');
        $this->command->info('📋 Users Created:');
        $this->command->info('Owner: ridhwan@example.com | Password: password');
        $this->command->info('Manager: ahsan@example.com | Password: password');
        $this->command->info('Staff: rizal@example.com | Password: password');
        $this->command->info('Guest: rapip@example.com | Password: password');
    }
}
