<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Room;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        Room::updated(function(Room $product){
            if($product->quantity == 0 && $product->isAbaliable()){
                $product->status = Room::UNAVAILABLE_PRODUCT;
                $product->save();
            }

        });

//        User::created(function(User $user){
//            retry(5, function() use ($user){
//                Mail::to($user)->send(new UserCreated($user));
//            }, 100);
//        });
//
//        User::updated(function(User $user){
//            if($user->isDirty('email')){
//                retry(5, function() use ($user){
//                    Mail::to($user)->send(new UserMailChanged($user));
//                }, 100);
//            }
//        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
