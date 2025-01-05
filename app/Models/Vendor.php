<?php
namespace App\Models;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Vendor extends User  implements ShouldQueue , JWTSubject
{
    use HasFactory, SoftDeletes;

    protected $date = ['deleted_at'];

    protected $guarded = [];

    protected $guard = 'vendor';

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

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'vendor_work_categories');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'vendor_work_brands');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
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
