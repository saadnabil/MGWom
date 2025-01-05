<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\CategoryProduct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::extend('custom', function($value, $key) {
    if ($value === 'image') {
        return 'image_' . $key;
    }
    return $value;
});
class ProductsSheetImport implements ToCollection, WithHeadingRow
{

    public function __construct()
    {
        // Set the formatter to custom
        HeadingRowFormatter::default('custom');
    }
    public function collection(Collection $rows)
    {


        foreach ($rows as $row) {
            DB::transaction(function () use ($row) {

                $thumbnail = $this->getFirstImage($row);

                $product = Product::create([
                    'name_en' => $row['name_en'],
                    'name_ar' => $row['name_ar'],
                    'desc_en' => $row['description_en'],
                    'desc_ar' => $row['description_ar'],
                    'price' => $row['original_price'],
                    'price_after_sale' =>$row['sale_price'] ? $row['sale_price'] : null,
                    'discount' => $row['sale_price'] ? ((($row['original_price'] - $row['sale_price'] )/ $row['original_price'] )* 100) : 0,
                    'model_id' => $row['model'],
                    'stock' => $row['stock'],
                    'brand_id' => $row['brand_id'],
                    'seller_sku' => $row['seller_sku'],
                    'vendor_id' => $row['vendor_id'],
                    'country_id' => 1,
                    'thumbnail' => $thumbnail,
                ]);

                // Insert categories
                $this->insertProductCategories($product->id, $row['categories_id']);
                $this->insertProductImages($product->id, $row);

            });
        }
    }


    private function insertProductCategories($productId, $categoriesId)
    {
        if (!empty($categoriesId)) {
            $categories = explode(',', $categoriesId);
            foreach ($categories as $categoryId) {
                CategoryProduct::create([
                    'product_id' => $productId,
                    'category_id' => $categoryId,
                ]);
            }
        }
    }


    private function getFirstImage($row)
    {
        // Loop through the row to find the first 'image_*' key with a value
        foreach ($row as $key => $value) {
            if (str_starts_with($key, 'image_') && !empty($value)) {
                return $value; // Return the first image found
            }
        }
        return null; // Return null if no image is found
    }

    private function insertProductImages($productId, $row)
    {
        // Collect all columns that start with 'image_' (from custom formatter)
        $images = [];
        foreach ($row as $key => $value) {
            if (str_starts_with($key, 'image_') && !empty($value)) {
                $images[] = $value;
                Log::info('Collected images for product ID ' . $key);

            }

        }

        // Log collected images for debugging
        Log::info('Collected images for product ID ' . $productId . ': ', $images);

        // Insert images if found
        if (!empty($images)) {
            foreach ($images as $image) {
                ProductImage::create([
                    'product_id' => $productId,
                    'image' => $image,
                ]);
            }
        } else {
            Log::warning('No images found for product ID ' . $productId);
        }
    }

}

