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
        view()->composer('distribute.*',function($view){      
            $view->with('ds_status',config('constants.ds_status'));
        });
        // view()->composer('distribute.edit',function($view){      
        //     $view->with('ds_status',config('constants.ds_status'));
        // });
        view()->composer('outletdistribute.*',function($view){      
            $view->with('ds_status',config('constants.ds_status'));
        });
        view()->composer('issue.*',function($view){      
            $view->with('ds_status',config('constants.ds_status'));
        });
        view()->composer('outletdistribute.*',function($view){      
            $view->with('counter_machine',config('constants.counter_machine'));
        });
        view()->composer('issue.*',function($view){      
            $view->with('branch',config('constants.branch'));
        });
        view()->composer('outlets.*',function($view){      
            $view->with('countries',config('constants.countries'));
        });
        view()->composer('outlets.*',function($view){      
            $view->with('cities',config('constants.cities'));
        });
        view()->composer('outlets.*',function($view){      
            $view->with('states',config('constants.states'));
            $view->with('types',config('constants.types'));
        });
        view()->composer('outletstockhistory.*',function($view){      
            $view->with('types',config('constants.types'));
            $view->with('branch',config('constants.branch'));            
        });
        view()->composer('issue.*',function($view){      
            $view->with('types',config('constants.types'));           
        });
        view()->composer('outletdistribute.*',function($view){      
            $view->with('types',config('constants.types'));           
        });
        view()->composer('outletlevelhistory.*',function($view){      
            $view->with('types',config('constants.types'));           
        });
        view()->composer('adjustment.*',function($view){      
            $view->with('adjustment_types',config('constants.adjustment_types'));           
        });
        view()->composer('damage.*',function($view){      
            $view->with('action',config('constants.action'));           
            $view->with('distination',config('constants.distination'));           
        });
        view()->composer('purchase.*',function($view){      
            $view->with('countries',config('constants.countries'));          
        });
        view()->composer('sell.*',function($view){      
            $view->with('payment_types',config('constants.payment_types'));          
        });
        
    }
}
