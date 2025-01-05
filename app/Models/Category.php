<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory , SoftDeletes;

    protected $guarded = [];

    protected $date = ['deleted_at'];

    public function getMainCategoryAllDescendantIds(){
        $descendantIds = collect();
        $this->allMainCategoryLevelChildren->each(function ($child) use (&$descendantIds) {
            $descendantIds = $descendantIds->merge([$child->id])
                                           ->merge($child->getMainCategoryAllDescendantIds());
        });
        return $descendantIds->unique();
    }

    public function getImageAttribute()
    {
        if (filter_var($this->attributes['image'], FILTER_VALIDATE_URL)) {
            // If the image is a valid URL, return it directly
            return $this->attributes['image'];
        } else {
            // If the image is not a URL, assume it's a file path and return it with the asset helper
            return asset('storage/' . $this->attributes['image']);
        }
    }

    public function children(){
        return $this->hasMany(Category::class, 'parent_id', 'id')->where('type','app_category');
    }

    public function parent(){
        return $this->belongsTo(Category::class, 'parent_id')->where('type','app_category');
    }

    public function allMainCategoryLevelChildren(){
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function brands(){
        return $this->belongsToMany(Brand::class , 'category_brands');
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'category_products');
    }

    public function salons(){
        return $this->hasMany(Salon::class);
    }

    public function getNameAttribute(){
        $lang = app()->getLocale();
        if($lang == 'en'){
            return $this->name_en;
        }
        return $this->name_ar;
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->withProperties($model->getAttributes())
                ->log('Create');
        });

        static::updated(function ($model) {
            $originalAttributes = $model->getOriginal();

            $attributes=[];
            foreach ($model->getDirty() as $attribute => $newValue) {
                $oldValue = $originalAttributes[$attribute] ?? null;

                if ($oldValue !== $newValue) {
                    $attributes[$attribute]['old']=$oldValue;
                    $attributes[$attribute]['new']=$newValue;
                }
            }

            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->withProperties($attributes)
                ->log('Updated');


        });

        static::deleting(function ($model) {
            $attributes = $model->getAttributes();

            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->withProperties($attributes)
                ->log('Delete');
        });
    }

}
