<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinGame extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function setDigitOneAttribute($value){
        $this->attributes['digit_one'] = ($value == 0) ? 'spin_again' : $value;
    }

    public function setDigitTwoAttribute($value){
        $this->attributes['digit_two'] = ($value == 0) ? 'spin_again' : $value;
    }

    public function setDigitThreeAttribute($value){
        $this->attributes['digit_three'] = ($value == 0) ? 'spin_again' : $value;
    }

    public function setDigitFourAttribute($value){
        $this->attributes['digit_four'] = ($value == 0) ? 'spin_again' : $value;
    }

    public function setDigitFiveAttribute($value){
        $this->attributes['digit_five'] = ($value == 0) ? 'spin_again' : $value;
    }

    public function setDigitSixAttribute($value){
        $this->attributes['digit_six'] = ($value == 0) ? 'spin_again' : $value;
    }

    public function setDigitSevenAttribute($value){
        $this->attributes['digit_seven'] = ($value == 0) ? 'spin_again' : $value;
    }

    public function setDigitEightAttribute($value){
        $this->attributes['digit_eight'] = ($value == 0) ? 'spin_again' : $value;
    }

    public function setDigitNineAttribute($value){
        $this->attributes['digit_nine'] = ($value == 0) ? 'spin_again' : $value;
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
