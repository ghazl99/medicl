<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            'دمشق',
            'حلب',
            'حمص',
            'حماة',
            'اللاذقية',
            'طرطوس',
            'إدلب',
            'الرقة',
            'دير الزور',
            'الحسكة',
            'درعا',
            'السويداء',
            'القنيطرة',
        ];
        foreach ($provinces as $province) {
            // إدخال المحافظة الأصلية
            $provinceId = City::create([
                'name' => $province,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ])->id;

            // إدخال الريف التابع لها
            City::create([
                'name' => 'ريف '.$province,
                'parent_id' => $provinceId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
