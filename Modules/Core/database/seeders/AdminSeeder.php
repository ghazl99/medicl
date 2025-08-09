<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Models\City;
use Modules\User\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstProvince = City::whereNull('parent_id')->first();

        $user = User::create([
            'name'             => 'أحمد',
            'email'            => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password'         => Hash::make('12345678'),
            'phone'            => '+90000000000',
            'workplace_name'   => 'إدارة النظام',
            'is_approved'      => 1,
        ])->assignRole('المشرف');

        if ($firstProvince) {
            $user->cities()->sync([$firstProvince->id]);
        }
    }
}
