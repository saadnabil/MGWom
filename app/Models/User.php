<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Resources\Api\CartResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPUnit\Framework\Constraint\Count;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use  HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    protected $guard = ["api"];

    protected $date = ['deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class)->withTrashed();
    }

    public function scratchgameusers()
    {
        return $this->hasMany(ScratchGameUser::class);
    }

    public function todayscratchgameuser()
    {
        return $this->hasOne(ScratchGameUser::class)->whereDate('date', Carbon::today());
    }

    public function favouriteproducts()
    {
        return $this->belongsToMany(Product::class, 'user_products');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }



    public function carts()
    {
        return $this->hasMany(Cart::class);
    }



    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function appliedcoupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }


    public function applied_coupons()
    {
        return $this->belongsToMany(Coupon::class, 'user_applied_coupons');
    }

    public function salon_bookings()
    {
        return $this->hasMany(SalonBooking::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function paymentcards()
    {
        return $this->hasMany(PaymentCard::class);
    }

    public function salonCartData()
    {
        $user = $this->load(['salon_bookings' => function ($q) {
            $q->where('status', 'in_cart');
        }, 'salon_bookings.details.expert', 'salon_bookings.details.service', 'country', 'applied_coupons']);
        $bookings = $user->salon_bookings;
        $data = [];
        $subtotal = 0;
        $total = 0;
        foreach ($bookings as $booking) {
            if (!isset($data[$booking->salon_id])) {
                $data[$booking->salon_id] = [
                    'salon' => $booking->salon->name,
                    'details' => [],
                ];
            }
            $bookingData = $booking->details->groupBy('expert.id')  // Group results by expert
                ->map(function ($bookingsByExpert) use (&$subtotal, &$total) {
                    $expert = $bookingsByExpert->first()->expert; // Access the first expert in the group

                    $bookings = $bookingsByExpert->map(function ($booking) use (&$subtotal, &$total) {
                        // Ensure related service is loaded
                        $booking->load('service');

                        // Increment subtotal and total
                        $subtotal += $booking->service->price ?? 0;
                        $total += $booking->service->price_after_sale ?? 0;

                        return [
                            'day' => $booking->reservation_date,
                            'time' => $booking->time,
                            'service' => $booking->service->name ?? '',  // Fallback to empty if service is missing
                            'price' => $booking->service->price ?? 0,
                            'price_after_sale' => $booking->service->price_after_sale ?? 0,
                            'discount' => $booking->service->discount ?? 0,
                        ];
                    });

                    return [
                        'expert' => [
                            'id' => $expert->id,
                            'name' => $expert->name,
                            'image' => $expert->image
                        ],
                        'bookings' => $bookings,
                    ];
                });

            $data[$booking->salon_id]['details'] = $bookingData->values(); // Reset keys for JSON output
        }
        // Prepare response data
        $salonAppliedcoupon = $user->applied_coupons->where('type', 'salon')->first();
        $response = [
            'salons' => array_values($data),
            'discount' =>  $salonAppliedcoupon ?  $salonAppliedcoupon->discount : 0,
            'subtotal' => round($subtotal, 2),
            'total' => $salonAppliedcoupon ?  round($total - ($total *  $salonAppliedcoupon->discount / 100), 2) : $total,
            'currency' => $user->country->currency ?? ''
        ];
        return $response;
    }

    public function cartData()
    {
        $this->load(['carts.product', 'carts.sku.sku', 'appliedcoupon']);
        $totalSub = 0;
        $tax  = 0;
        $shipping = count($this->carts) > 0 ?  20 : 0;
        $discount = $this->appliedcoupon ? $this->appliedcoupon->discount : 0;

        $this->carts->each(function ($cart) use (&$totalSub) {
            $cartsku = $cart->sku;
            if (!$cartsku) {
                $cart->price = $cart->quantity * $cart->product->price;
                $cart->price_after_sale = $cart->quantity * $cart->product->price_after_sale;
                //sum cart final
                $totalSub += $cart->price_after_sale;
                //sum cart final
            } else {
                $cartsku->load('sku');
                $cart->price = $cart->quantity * $cartsku->sku->price;
                $cart->price_after_sale = $cart->quantity * $cartsku->sku->price_after_sale;
                //sum cart final
                $totalSub += $cart->price_after_sale;
                //sum cart final
            }
        });

        /**Calculate Order Total */
        $total = $totalSub;
        $total = $total + ($total * $tax / 100);
        $total = $total - ($total * $discount / 100);
        $total = $total + $shipping;
        /**Calculate Order Total */

        $data = [
            'vat' => $tax,
            'shipping' => $shipping,
            'total' =>  round($total, 2),
            'totalSub' => round($totalSub, 2),
            'discount' => $discount,
            'details' => CartResource::collection($this->carts),
        ];
        return $data;
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

            $attributes = [];
            foreach ($model->getDirty() as $attribute => $newValue) {
                $oldValue = $originalAttributes[$attribute] ?? null;

                if ($oldValue !== $newValue) {
                    $attributes[$attribute]['old'] = $oldValue;
                    $attributes[$attribute]['new'] = $newValue;
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
