<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        //
        view()->composer('riders.index',function($view){      
            $view->with('ridertypes',config('constants.ridertypes'));
        });
        view()->composer('riders.create',function($view){      
            $view->with('ridertypes',config('constants.ridertypes'));
        });
        view()->composer('riders.show',function($view){      
            $view->with('ridertypes',config('constants.ridertypes'));
        });
        view()->composer('riders.view',function($view){      
            $view->with('ridertypes',config('constants.ridertypes'));
        });
        view()->composer('riders.edit',function($view){      
            $view->with('ridertypes',config('constants.ridertypes'));
        });
        view()->composer('components.detail-card',function($view){      
            $view->with('ridertypes',config('constants.ridertypes'));
        });
    }
}
