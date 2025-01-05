<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function image(){
        return $this->belongsTo(Product::class);
    }

    public function getImagePathAttribute()
    {
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            // If the image is a valid URL, return it directly
            return $this->image;
        } else {
            // If the image is not a URL, assume it's a file path and return it with the asset helper
            return url('storage/' . $this->image);
        }
    }

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

