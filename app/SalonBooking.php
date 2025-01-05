<?php
namespace App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SalonBooking extends Model
{
    use HasFactory, SoftDeletes;
    protected $date = ['deleted_at'];
    protected $guarded = [];
    public function services(){
        return $this->hasMany(SalonBookingService::class);
    }
}
