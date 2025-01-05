<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartSku extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sku(){
        return $this->belongsTo(ProductVariantSku::class);
    }
}
