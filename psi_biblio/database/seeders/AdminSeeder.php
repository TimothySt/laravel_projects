<?php

namespace Database\Seeders;

// W pliku AdminSeeder.php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;
use App\Models\Role;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Utwórz rolę administratora, jeśli nie istnieje
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // // Utwórz pierwszego administratora
        // Member::firstOrCreate([
        //     'first_name' => 'Admin',
        //     'last_name' => 'Admin',
        //     'email' => 'admin@admin.admin',
        //     'tel' => '999999999',
        //     'address' => 'Admin Address',
        //     'password' => Hash::make('adminadmin'),
        //     'role_id' => $adminRole->role_id,
        // ]);

        Member::updateOrCreate(
            ['email' => 'admin@admin.admin'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'tel' => '999999999',
                'address' => 'Admin Address',
                'password' => Hash::make('adminadmin'),
                'role_id' => $adminRole->role_id,
            ]
        );

        // stworzenie employee
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        Member::updateOrCreate(
            ['email' => 'eployee@employee.employee'],
            [
                'first_name' => 'Employee',
                'last_name' => 'Employee',
                'tel' => '888888888',
                'address' => 'Employee Address',
                'password' => Hash::make('employee'),
                'role_id' => $employeeRole->role_id,
            ]
        );
        
        // stworzenie user
        $userRole = Role::firstOrCreate(['name' => 'user']);
        Member::updateOrCreate(
            ['email' => 'user@user.user'],
            [
                'first_name' => 'User',
                'last_name' => 'User',
                'tel' => '777777777',
                'address' => 'User Address',
                'password' => Hash::make('  '),
                'role_id' => $userRole->role_id,
            ]
        );
        
    }
}
