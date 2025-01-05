<?php

namespace App\Models;

use App\Services\ElasticSearch\ElasticSearchService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory ,SoftDeletes;
    protected $guarded = [];
    protected $date = ['deleted_at'];


    public function getNameAttribute(){
        $lang = app()->getLocale();
        if($lang == 'en'){
            return $this->name_en;
        }
        return $this->name_ar;
    }



    public function getDescAttribute(){
        $lang = app()->getLocale();
        if($lang == 'en'){
            return $this->desc_en;
        }
        return $this->desc_ar;
    }




    

    public function getThumbnailAttribute()
    {
        if (filter_var($this->attributes['thumbnail'], FILTER_VALIDATE_URL)) {
            // If the image is a valid URL, return it directly
            return $this->attributes['thumbnail'];
        } else {
            // If the image is not a URL, assume it's a file path and return it with the asset helper
            return asset('storage/' . $this->attributes['thumbnail']);
        }
    }

    public function getMaterialAttribute(){
        $lang = app()->getLocale();
        if($lang == 'en'){
            return $this->material_en;
        }
        return $this->material_ar;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_products');
    }

    public function designer(){
        return $this->belongsTo(Designer::class);
    }

    public function images(){
        return $this->hasMany(ProductImage::class);
    }

    // public function variants(){
    //     return $this->hasMany(ProductVariant::class);
    // }

    public function favoritedbyusers()
    {
        return $this->belongsToMany(User::class, 'user_products');
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    // public function colors(){
    //     return $this->hasMany(ProductColor::class);
    // }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }

    // public function skus(){
    //     return $this->hasMany(ProductVariantSku::class);
    // }

    public function specifications(){
        return $this->hasMany(ProductSpecification::class);
    }


    public function skus() {
        return $this->hasMany(ProductVariantSku::class);
    }

    // public function colors() {
    //     return $this->hasManyThrough(ProductColor::class, ProductVariantSku::class, 'product_id', 'sku_id');
    // }

    public function colors() {
        return $this->hasMany(ProductColor::class);
    }

    public function variants() {
        return $this->hasMany(ProductVariant::class);
    }

    // public function variants() {
    //     return $this->hasManyThrough(ProductVariant::class, ProductVariantSku::class, 'product_id', 'sku_id');
    // }

    /**Elastic /search */
    // protected static function booted()
    // {
    //     static::created(function ($product) {
    //         (new ElasticSearchService())->indexProduct($product);
    //     });

    //     static::updated(function ($product) {
    //         (new ElasticSearchService())->indexProduct($product);
    //     });
    // }



    // protected static function boot()
    // {
    //     parent::boot();
    //     static::created(function ($model) {
    //         activity()
    //             ->performedOn($model)
    //             ->causedBy(auth()->user())
    //             ->withProperties($model->getAttributes())
    //             ->log('Create');
    //     });

    //     static::updated(function ($model) {
    //         $originalAttributes = $model->getOriginal();

    //         $attributes=[];
    //         foreach ($model->getDirty() as $attribute => $newValue) {
    //             $oldValue = $originalAttributes[$attribute] ?? null;

    //             if ($oldValue !== $newValue) {
    //                 $attributes[$attribute]['old']=$oldValue;
    //                 $attributes[$attribute]['new']=$newValue;
    //             }
    //         }

    //         activity()
    //             ->performedOn($model)
    //             ->causedBy(auth()->user())
    //             ->withProperties($attributes)
    //             ->log('Updated');


    //     });

    //     static::deleting(function ($model) {
    //         $attributes = $model->getAttributes();

    //         activity()
    //             ->performedOn($model)
    //             ->causedBy(auth()->user())
    //             ->withProperties($attributes)
    //             ->log('Delete');
    //     });
    // }



}
