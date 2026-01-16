<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\NotifikasiController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Carbon::setLocale(app()->getLocale());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        setlocale(LC_ALL, 'id_ID.UTF-8');
        Carbon::setLocale('id_ID.UTF-8');

        view()->composer('layouts.app', function($view)
        {
            $notifikasiController = new NotifikasiController;
            $notif = $notifikasiController->index();
            // dd($notif['nonLingkungan']);

            $view->with('notif', $notif);

            // Pending drafts count for superadmin
            $pendingDraftsCount = 0;
            if (auth()->check() && auth()->user()->isSuperAdmin()) {
                // Uncomment when Draft model is created:
                // $pendingDraftsCount = \App\Models\Draft::where('status', 'pending')->count();
            }
            $view->with('pendingDraftsCount', $pendingDraftsCount);
        });
    }
}
