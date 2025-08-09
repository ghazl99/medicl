<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Category\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        // المجموعات الرئيسية مع أبناء
        $categories = [
            'اجنبي' => [
                'حقن',
                'حبوب',
                'شرابات',
                'تحاميل',
            ],
            'اكسسوار' => [
                'منتجات الاطفال والأمومة',
                'معدات طبية',
                'جنسيات',
                'منتجات الشعر والجسم',
                ' كمامات ومشدات','ضمادات'
            ],
            'تجميلي' => [
                'واقيات شمس',
                'كريمات',
                'سيرومات','غسولات'
            ],
            'تغذية' => [
                'حليب',
                'فيتامينات',
                'اعشاب',
                'بروتينات',
                'مستحضرات تنحيف',
            ],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::create(['name' => $parentName]);

            foreach ($children as $childName) {
                Category::create([
                    'name' => $childName,
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}
