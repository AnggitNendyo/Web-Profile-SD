<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        Role::create(['name' => 'super-admin']);
        Role::create(['name' => 'operator']);
        Role::create(['name' => 'guru']);

        // Create default super-admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sdnegeri.sch.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('super-admin');

        // Create operator user
        $operator = User::create([
            'name' => 'Operator Sekolah',
            'email' => 'operator@sdnegeri.sch.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $operator->assignRole('operator');
    }
}
