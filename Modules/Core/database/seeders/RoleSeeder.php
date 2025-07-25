<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'المشرف']);
        Role::create(['name' => 'صيدلي']);
        Role::create(['name' => 'مورد']);
    }
}
