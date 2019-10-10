<?php

namespace SanjabHelpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use SanjabHelpers\Models\City;
use SanjabHelpers\Models\Province;

class SanjabHelpersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if (config('sanjab_helpers.geo')) {
            $this->loadMigrationsFrom([
                realpath(__DIR__.'/../database/migrations/2014_10_11_000000_create_provinces_table.php'),
                realpath(__DIR__.'/../database/migrations/2014_10_11_000001_create_cities_table.php')
            ]);
        }
        if (config('sanjab_helpers.aparat')) {
            $this->loadMigrationsFrom([
                realpath(__DIR__.'/../database/migrations/2014_10_11_000002_create_aparat_videos_table.php')
            ]);
        }

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('sanjab_helpers.php'),
        ], 'config');

        Validator::resolver(function ($translator, $data, $rules, $messages = [], $customAttributes = []) {
            return new SanjabValidator($translator, $data, $rules, $messages, $customAttributes);
        });

        if (config('sanjab_helpers.geo')) {
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
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'sanjab_helpers');
        app(\Faker\Generator::class)->addProvider(new SanjabFakerProvider(app(\Faker\Generator::class)));
    }
}
