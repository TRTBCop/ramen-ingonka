<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment() !== 'production') {
            // Add in boot function
            DB::listen(function ($query) {
                File::append(
                    storage_path('/logs/query.log'),
                    PHP_EOL.$query->sql.PHP_EOL.'['.implode(', ', $query->bindings).']'.PHP_EOL.PHP_EOL
                );
            });
        }

        // ngrok 사용시 요청 url 찾기위해
        if (request()->server->has('HTTP_X_FORWARDED_HOST')) {
            $this->app['url']->forceRootUrl(request()->server->get('HTTP_X_FORWARDED_PROTO').'://'.request()->server->get('HTTP_X_FORWARDED_HOST'));
            $this->app['url']->forceScheme('https');
        }

        if (request()->server->get('HTTP_X_FORWARDED_PROTO')) {
            $this->app['url']->forceScheme('https');
        }


        Validator::extend('strict_integer', function ($attribute, $value, $parameters, $validator) {
            return is_int($value);
        });

        Validator::extend('strict_string', function ($attribute, $value, $parameters, $validator) {
            return is_string($value);
        });
    }
}
