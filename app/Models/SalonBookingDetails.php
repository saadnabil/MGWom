<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalonBookingDetails extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function salon(){
        return $this->belongsTo(Salon::class , 'salon_id' , 'id')->withTrashed();
    }

    public function branch(){
        return $this->belongsTo(SalonBranch::class , 'salon_branch_id' , 'id')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }

    public function expert(){
        return $this->belongsTo(Expert::class ,'expert_id' , 'id')->withTrashed();
    }

    public function service(){
        return $this->belongsTo(SalonBranchService::class , 'salon_branch_service_id' , 'id')->withTrashed();
    }

    public function booking(){
        return $this->belongsTo(SalonBooking::class)->withTrashed();
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
