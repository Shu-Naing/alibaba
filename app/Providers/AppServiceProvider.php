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
        view()->composer('components.detail-card',function($view){      
            $view->with('ridertypes',config('constants.ridertypes'));
        });
        view()->composer('distribute.create',function($view){      
            $view->with('ds_status',config('constants.ds_status'));
        });
        view()->composer('distribute.edit',function($view){      
            $view->with('ds_status',config('constants.ds_status'));
        });
        view()->composer('outlets.*',function($view){      
            $view->with('countries',config('constants.countries'));
        });
        view()->composer('outlets.*',function($view){      
            $view->with('cities',config('constants.cities'));
        });
        view()->composer('outlets.*',function($view){      
            $view->with('states',config('constants.states'));
        });
    }
}
