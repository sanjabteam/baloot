<?php

namespace Baloot;

use Baloot\Models\City;
use Baloot\Models\Province;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class BalootServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if (config('baloot.geo')) {
            $this->loadMigrationsFrom([
                realpath(__DIR__.'/../database/migrations/2014_10_11_000000_create_provinces_table.php'),
                realpath(__DIR__.'/../database/migrations/2014_10_11_000001_create_cities_table.php'),
            ]);
        }
        if (config('baloot.aparat')) {
            $this->loadMigrationsFrom([
                realpath(__DIR__.'/../database/migrations/2014_10_11_000002_create_aparat_videos_table.php'),
            ]);
        }

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('baloot.php'),
        ], 'config');

        Validator::resolver(function ($translator, $data, $rules, $messages = [], $customAttributes = []) {
            return new BalootValidator($translator, $data, $rules, $messages, $customAttributes);
        });

        if (config('baloot.geo')) {
            foreach (['city' => City::class, 'province' => Province::class] as $key => $model) {
                Route::bind($key, function ($value) use ($model) {
                    return $model::where('slug', $value)->orWhere('id', $value)->firstOrFail();
                });
                Route::bind($key.'_by_slug', function ($value) use ($model) {
                    return $model::where('slug', $value)->firstOrFail();
                });
                Route::bind($key.'_by_id', function ($value) use ($model) {
                    return $model::where('id', $value)->firstOrFail();
                });
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'baloot');
        app(\Faker\Generator::class)->addProvider(new BalootFakerProvider(app(\Faker\Generator::class)));
    }
}
