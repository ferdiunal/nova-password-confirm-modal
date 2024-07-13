<?php

namespace Ferdiunal\NovaPasswordConfirmModal;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-password-confirm-modal', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-password-confirm-modal', __DIR__.'/../dist/css/field.css');
            Nova::translations(realpath(__DIR__.sprintf('/../lang/%s.json', $this->app->getLocale())));
        });

        Route::middleware(['nova'])
            ->prefix('nova-vendor/ferdiunal/nova-password-confirm-modal')
            ->group(__DIR__.'/../routes/api.php');
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
