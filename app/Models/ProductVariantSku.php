<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantSku extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $date = ['deleted_at'];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    // public function variants(){
    //     return $this->hasMany(ProductVariant::class, 'sku_id');
    // }

    // public function colors(){
    //     return $this->hasMany(ProductColor::class, 'sku_id');
    // }

    public function color() {
        return $this->hasOne(ProductColor::class, 'sku_id');
    }

    public function variant() {
        return $this->hasOne(ProductVariant::class, 'sku_id');
    }

}
