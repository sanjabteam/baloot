<?php

namespace Baloot;

use Baloot\Models\City;
use Baloot\Models\Province;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class BalootServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('baloot.php'),
        ], 'config');

        Validator::resolver(function ($translator, $data, $rules, $messages = [], $customAttributes = []) {
            return new BalootValidator($translator, $data, $rules, $messages, $customAttributes);
        });

        if (config('baloot.geo')) {
            $this->loadMigrationsFrom([
                realpath(__DIR__.'/../database/migrations/2014_10_11_000000_create_provinces_table.php'),
                realpath(__DIR__.'/../database/migrations/2014_10_11_000001_create_cities_table.php'),
            ]);

            $this->bindCityRoutes();
        }

        $this->registerQueryBuilderMacros();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'baloot');
        app(\Faker\Generator::class)->addProvider(new BalootFakerProvider(app(\Faker\Generator::class)));
    }

    /**
     * Register query builder macros.
     */
    public function registerQueryBuilderMacros()
    {
        Builder::macro('whereJalali', function (string $column, $operator, $value = null, $boolean = 'and') {
            /**
             * @var Builder $this
             */
            [$value, $operator] = $this->prepareValueAndOperator($value, $operator, func_num_args() === 2);
            if (! $value instanceof Verta) {
                $value = Verta::parse($value);
            }
            $this->where($column, $operator, $value->DateTime(), $boolean);

            return $this;
        });

        Builder::macro('whereDateJalali', function (string $column, $operator, $value = null, $boolean = 'and') {
            /**
             * @var Builder $this
             */
            [$value, $operator] = $this->prepareValueAndOperator($value, $operator, func_num_args() === 2);
            if (! $value instanceof Verta) {
                $value = Verta::parse($value);
            }
            $this->whereDate($column, $operator, $value->DateTime(), $boolean);

            return $this;
        });

        Builder::macro('whereInMonthJalali', function (string $column, $month, $year = null) {
            /**
             * @var Builder $this
             */
            $year = $year ? $year : verta()->year;
            $this->where(function ($query) use ($column, $month, $year) {
                $query->whereDate($column, '>=', Verta::createJalaliDate($year, $month, 1)->DateTime())
                    ->whereDate($column, '<', Verta::createJalaliDate($year, $month, 1)->addMonth()->DateTime());
            });

            return $this;
        });

        Builder::macro('whereInYearJalali', function (string $column, $year = null) {
            /**
             * @var Builder $this
             */
            $year = $year ? $year : verta()->year;
            $this->where(function ($query) use ($column, $year) {
                $query->whereDate($column, '>=', Verta::createJalaliDate($year, 1, 1)->DateTime())
                    ->whereDate($column, '<', Verta::createJalaliDate($year, 1, 1)->addYear()->DateTime());
            });

            return $this;
        });

        Builder::macro('whereBetweenJalali', function (string $column, array $values, $boolean = 'and', $not = false) {
            /**
             * @var Builder $this
             */
            foreach ($values as $index => $value) {
                if (! $value instanceof Verta) {
                    $value = Verta::parse($value);
                }
                $values[$index] = $value->DateTime();
            }
            $this->whereBetween($column, $values, $boolean, $not);

            return $this;
        });

        Builder::macro('whereNotBetweenJalali', function (string $column, array $values, $boolean = 'and', $not = false) {
            $this->whereBetweenJalali($column, $values, $boolean, true);

            return $this;
        });
    }

    private function bindCityRoutes()
    {
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
