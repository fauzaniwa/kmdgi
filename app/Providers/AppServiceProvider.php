<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Footer;

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
        // Menyuntikkan data $footerSetting ke komponen partials.footer secara global
        View::composer('partials.footer', function ($view) {
            
            // Panggil langsung dari database tanpa Cache untuk menghindari error Incomplete Object
            if (Schema::hasTable('footers')) {
                $footerSetting = Footer::first() ?? new Footer();
            } else {
                // Fallback object kosong jika tabel belum di-migrate
                $footerSetting = new Footer(); 
            }

            $view->with('footerSetting', $footerSetting);
        });
    }
}