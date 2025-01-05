<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function getNameAttribute(){
        $lang = app()->getLocale();
        if($lang == 'en'){
            return $this->name_en;
        }
        return $this->name_ar;
    }

    public function getValueAttribute(){
        $lang = app()->getLocale();
        if($lang == 'en'){
            return $this->value_en;
        }
        return $this->value_ar;
    }


}
