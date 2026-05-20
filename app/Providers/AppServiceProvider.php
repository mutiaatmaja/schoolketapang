<?php

namespace App\Providers;

use App\Models\SchoolInformation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.ppdb.app', function (\Illuminate\View\View $view): void {
            $schoolInfo = SchoolInformation::query()->ordered()->pluck('value', 'label')->all();
            $view->with('schoolInfo', $schoolInfo);
        });
    }
}
