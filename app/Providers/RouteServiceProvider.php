<?php

/*
 * This file is part of the caikeal/fourteen_unrelated .
 *
 * (c) caikeal <caiyuezhang@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        $this->mapWebRoutes();

        $this->mapStaffRoutes();

        $this->mapUserRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * 员工端.
     *
     * @author Caikeal <caikeal@qq.com>
     */
    protected function mapStaffRoutes()
    {
        Route::prefix('staff.php')
            ->middleware('api')
            ->namespace('App\Http\Controllers\Staff\Api')
            ->group(base_path('routes/staff.php'));
    }

    protected function mapUserRoutes()
    {
        Route::prefix('user')
            ->middleware('api')
            ->namespace('App\Http\Controllers\User\Api')
            ->group(base_path('routes/user.php'));
    }
}
