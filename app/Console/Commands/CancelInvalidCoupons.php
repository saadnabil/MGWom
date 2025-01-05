<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CancelInvalidCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cancelinvalidcoupons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel Invalid Coupons (expired)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $countries = Country::get();
        foreach($countries as $country){
                Coupon::where('country_id', $country->id)
                        ->where('expiration_date', '<', Carbon::today($country->timezone))
                        ->update([
                            'status' => 'expired',
                        ]);
        }
    }
}
