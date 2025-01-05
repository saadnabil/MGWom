<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductSpecification;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SpecificationsSheetImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $product = Product::where('seller_sku', $row['seller_sku'])->first();

            if ($product) {
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'name_en' => $row['name_en'],
                    'value_en' => $row['value_en'],
                    'name_ar' => $row['name_ar'],
                    'value_ar' => $row['value_ar'],
                ]);
            }
        }
    }
}
