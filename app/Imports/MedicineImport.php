<?php

namespace App\Imports;

// use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Category\Models\Category;

class MedicineImport implements ToModel, WithHeadingRow // ,ShouldQueue, WithChunkReading
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $categoryName = $row['الصنف الفرعي'];

        $parentCategory = Category::firstOrCreate(
            ['name' => $row['الصنف الرئيسي']],
            ['parent_id' => null]
        );

        $category = Category::firstOrCreate(
            ['name' => $categoryName, 'parent_id' => $parentCategory->id]
        );

        return new \Modules\Medicine\Models\Medicine([
            'category_id' => $category->id,
            'type' => $row['النوع'],
            'composition' => $row['التركيب'],
            'form' => $row['الشكل'],
            'company' => $row['الشركة'],
            'note' => $row['ملاحظات'],
            'net_dollar_new' => $row['النت دولار الجديد'],
            'public_dollar_new' => $row['العموم دولار الجديد'],
        ]);
    }

    // public function chunkSize(): int
    // {
    //     return 1000;
    // }
}
