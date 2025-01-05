<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductColor extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $date = ['deleted_at'];


    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function color(){
        return $this->belongsTo(Color::class);
    }

    public function sku(){
        return $this->belongsTo(ProductVariantSku::class);
    }

    public function colorsizes(){
        return $this->hasManyThrough(ProductVariant::class, ProductVariantSku::class);
    }
}
