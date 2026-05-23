<?php

namespace App\Providers;

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
    \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
        if (auth()->check()) {
            $notifikasi = \App\Models\DetailPinjam::with(['pinjam.user', 'buku'])
                ->whereHas('pinjam', function($q) {
                    $q->where('status', 'pinjam');
                    if (!auth()->user()->isAdmin()) {
                        $q->where('user_id', auth()->id());
                    }
                })
                ->where(function($q) {
                    $q->where('tgl_kembali_estimasi', '<', now()->toDateString())
                      ->orWhereBetween('tgl_kembali_estimasi', [
                          now()->toDateString(),
                          now()->addDays(1)->toDateString()
                      ]);
                })
                ->get()
                ->map(fn($dp) => [
                    'nama'  => optional($dp->pinjam->user)->nama,
                    'judul' => $dp->buku->judul,
                    'tgl'   => $dp->tgl_kembali_estimasi,
                    'sisa'  => now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($dp->tgl_kembali_estimasi)->startOfDay(), false),
                    'type'  => \Carbon\Carbon::parse($dp->tgl_kembali_estimasi)->isPast() ? 'terlambat' : 'hampir',
                ])
                ->sortBy('sisa')
                ->values();

            $notifCount = $notifikasi->count();

            $view->with(compact('notifikasi', 'notifCount'));
        }
    });
}
}
