<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(255);

        \Response::macro('attachment', function ($content, $name, $contentType, $end) {
            $headers = [
                'Content-type' => $contentType . '; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $name . '.' . $end . '"',
            ];
            return response($content, 200, $headers);
        });
    }
}
