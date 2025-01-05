<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImagesSheetImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $product = Product::where('seller_sku', $row['seller_sku'])->first();

            if ($product) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $row['image'],
                ]);
            }
        }
    }
}
