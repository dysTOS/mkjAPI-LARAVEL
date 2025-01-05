<?php

namespace App\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client;

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

        Storage::extend('nextcloud', function (Application $app, array $config) {
            $client = new Client([
                'baseUri' => $config['url'],
                'userName' => $config['user'],
                'password' => $config['password']
            ]);
            $adapter = new WebDAVAdapter($client);

            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });

        \Response::macro('attachment', function ($content, $name, $contentType, $end) {
            $headers = [
                'Content-type' => $contentType . '; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $name . '.' . $end . '"',
            ];
            return response($content, 200, $headers);
        });
    }
}
