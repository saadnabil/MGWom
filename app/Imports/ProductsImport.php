<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductsImport implements WithMultipleSheets
{
    // private $productsData;


    // public function __construct($productsData, )
    // {
    //     $this->productsData = $productsData;
    // }


    public function sheets(): array
    {
        return [
            'Products' => new ProductsSheetImport(),
            'Attributes' => new AttributesSheetImport(),
            'Specifications' => new SpecificationsSheetImport(),
        ];
    }
}
