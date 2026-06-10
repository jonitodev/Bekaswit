<?php

/** @author Mochamad Yunan Helmy Affandi - 244107020101 */

namespace App\Providers;

use App\Models\Barang;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('layouts.admin', function ($view) {
            $view->with('pendingApprovalCount', Barang::where('approval_status', 'pending')->count());
        });
    }
}
