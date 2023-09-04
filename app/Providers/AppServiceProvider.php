<?php

namespace App\Providers;

use App\Services\FilesUploader;
use App\Services\Interfaces\FilesUploaderInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FilesUploaderInterface::class, FilesUploader::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::anonymousComponentPath(resource_path('Dashboard'), 'dashboard');
        Paginator::useBootstrapFour();
    }
}
