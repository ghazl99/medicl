<?php

namespace App\Imports;

use Modules\Category\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class MedicineImport implements ShouldQueue, ToModel, WithChunkReading, WithHeadingRow
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
            'note_2' => $row['ملاحظات_2'] ?? $row['ملاحظات'], // إذا نفس العمود
            'price_change_percentage' => $row['نسبة الغلاء او الرخص'] ?? null,
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
