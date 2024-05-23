<?php

namespace Database\Seeders;

// database/seeders/RoleSeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Usuń wszystkie wiersze z tabeli roles
        DB::table('roles')->delete();
        
        $roles = [
            ['role_id' => 1,'name' => 'user'],
            ['role_id' => 2,'name' => 'employee'],
            ['role_id' => 3,'name' => 'admin'],
        ];

        // DB::table('roles')->insert($roles);
        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
