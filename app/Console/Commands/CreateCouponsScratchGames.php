<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\ScratchGame;
use App\Models\ScratchGameUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
class CreateCouponsScratchGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:scratchgames';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates scratch games for each user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $countries = Country::with('users')->get();
        foreach($countries as $country){

            $date = Carbon::now($country->timezone);

            /*get scratch game for today*/
            $todayscratchgame = ScratchGame::where([
                'date' => $date->format('Y-m-d'),
                'country_id' => $country->id
            ])->first();

            /*create a new one if time is 12:00 am*/
            if(!$todayscratchgame){

                /*get scratch game discount*/
                $scratchSystemDiscount = getScratchGameDiscount($country);

                $scratchgame = ScratchGame::create([
                    'code' => strtoupper(Str::random(5)),
                    'discount' => (int)$scratchSystemDiscount,
                    'country_id' => $country->id,
                    'date' => $date->format('Y-m-d'),
                ]);

                foreach($country->users as $user){
                    ScratchGameUser::create([
                        'user_id' => $user->id,
                        'scratch_game_id' => $scratchgame->id,
                        'date' => $date->format('Y-m-d'),
                        'expiration_date' => $date->copy()->addDays(3)->format('Y-m-d'),
                        'open_cell_index' => 0,
                    ]);
                }

            }
        }
    }
}
