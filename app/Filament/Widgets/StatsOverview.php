<?php

namespace App\Filament\Widgets;

use App\Models\Claim;
use App\Models\Item;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Barang Hilang Aktif', Item::where('tipe', 'hilang')->where('status', 'hilang')->count())
                ->description('Menunggu ditemukan')
                ->descriptionIcon('heroicon-m-magnifying-glass')
                ->color('danger'),

            Stat::make('Barang Temuan Terbuka', Item::where('tipe', 'temuan')->where('status', 'ditemukan')->count())
                ->description('Siap diklaim pemilik')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('info'),

            Stat::make('Klaim Menunggu Verifikasi', Claim::where('status', 'menunggu')->count())
                ->description('Perlu tindakan persetujuan')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Berhasil Dikembalikan', Item::where('status', 'dikembalikan')->count())
                ->description('Telah kembali ke pemilik')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
