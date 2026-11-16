<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:dashboard.view')->name('dashboard');
        Route::resource('members', MemberController::class)
            // ->middleware('permission:members.view')
            // ->names([
            //     'index' => 'members.index',
            //     'create' => 'members.create',
            //     'store' => 'members.store',
            //     'show' => 'members.show',
            //     'edit' => 'members.edit',
            //     'update' => 'members.update',
            //     'destroy' => 'members.destroy',
            // ])
            ;

    });
