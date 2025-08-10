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
        $categoryName = $row['الصنف'];

        // نبحث عن الصنف أو ننشئه إذا غير موجود
        $category = Category::firstOrCreate(['name' => $categoryName]);

        return new \Modules\Medicine\Models\Medicine([
            'category_id' => $category->id,
            'type' => $row['النوع'],
            'composition' => $row['التركيب'],
            'form' => $row['الشكل'],
            'company' => $row['الشركة'],
            'note' => $row['ملاحظات'],
            'net_dollar_old' => $row['نت دولار حالي'],
            'public_dollar_old' => $row['عموم دولار حالي'],
            'net_dollar_new' => $row['النت دولار الجديد'],
            'public_dollar_new' => $row['العموم دولار الجديد'],
            'net_syp' => $row['نت سوري'],
            'public_syp' => $row['عموم سوري'],
            'price_change_percentage' => $row['نسبة الغلاء او الرخص'] ?? null,
        ]);
    }

    // public function chunkSize(): int
    // {
    //     return 1000;
    // }
}
