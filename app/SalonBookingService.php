<?php

namespace App;

use App\Models\Expert;
use App\Models\Salon;
use App\Models\SalonBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class SalonBookingService extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected function salon(){
        return $this->belongsTo(Salon::class)->withTrashed();
    }
    protected function booking(){
        return $this->belongsTo(SalonBooking::class)->withTrashed();
    }
    protected function expert(){
        return $this->belongsTo(Expert::class)->withTrashed();
    }
    protected function branch(){
        return $this->belongsTo(SalonBranch::class)->withTrashed();
    }
}
