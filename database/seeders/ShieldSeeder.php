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
        $resources = ['category', 'customer', 'product', 'order', 'user', 'permission'];
        $actions = ['view', 'view_any', 'create', 'update', 'delete', 'delete_any'];
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::create([
                    'name' => "{$action}_{$resource}",
                    'guard_name' => 'web'
                ]);
            }
        }
        Permission::create(['name' => 'view_shield::role', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_any_shield::role', 'guard_name' => 'web']);
        Permission::create(['name' => 'create_shield::role', 'guard_name' => 'web']);
        Permission::create(['name' => 'update_shield::role', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete_shield::role', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete_any_shield::role', 'guard_name' => 'web']);
        $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $user = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());
        $adminPermissions = Permission::where('name', 'like', 'view%')
            ->orWhere('name', 'like', 'create%')
            ->orWhere('name', 'like', 'update%')
            ->get();
        $admin->givePermissionTo($adminPermissions);
        $userPermissions = Permission::where('name', 'like', 'view%')
            ->orWhere('name', 'like', 'view_any%')
            ->get();
        $user->givePermissionTo($userPermissions);
        $existingAdmin = User::where('email', 'lrv94451@gmail.com')->first();
        if ($existingAdmin) {
            $existingAdmin->assignRole('super_admin');
            $this->command->info('✅ Existing admin user assigned as super_admin');
        }
        $demoAdmin = User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $demoAdmin->assignRole('admin');
        $demoUser = User::create([
            'name' => 'Demo User',
            'email' => 'user@demo.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $demoUser->assignRole('user');
        $this->command->info('✅ Roles and Permissions created successfully!');
        $this->command->info('');
        $this->command->info('📋 Demo Users:');
        $this->command->info('Super Admin: lrv94451@gmail.com (existing password)');
        $this->command->info('Admin: admin@demo.com | Password: password');
        $this->command->info('User: user@demo.com | Password: password');
    }
}
