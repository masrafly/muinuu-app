<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\View\View; 
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    // protected static string $view = 'filament.pages.dashboard';

    // Tambahkan ini jika Anda ingin mengontrol akses ke halaman Dashboard ini
    // public static function canAccess(): bool
    // {
    //     // Pastikan user sedang login
    //     if (!Auth::check()) {
    //         return false;
    //     }

    //     // Dapatkan user yang sedang login
    //     $user = Auth::user();

    //     // Izinkan akses jika user adalah admin ATAU staff
    //     return $user->isAdmin() || $user->hasRole('staff');
    // }
}