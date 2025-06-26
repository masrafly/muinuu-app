<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard/login');
Route::get('/', function () {
    return redirect()->route('filament.dashboard.auth.login');
});