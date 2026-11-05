<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoryController extends Controller
{
    /**
     * Tampilkan halaman Riwayat Saya dengan tab "Laporan Saya" dan "Klaim Saya".
     */
    public function index(Request $request): View
    {
        $activeTab = $request->input('tab', 'laporan');
        if (! in_array($activeTab, ['laporan', 'klaim'], true)) {
            $activeTab = 'laporan';
        }

        $user = auth()->user();

        // 1. Laporan Saya
        $myItems = $user->items()
            ->with(['category', 'location', 'pickupPoint', 'approvedClaim.claimer'])
            ->withCount('claims')
            ->latest()
            ->paginate(10, ['*'], 'laporan_page')
            ->withQueryString();

        // 2. Klaim Saya
        $myClaims = $user->claims()
            ->with(['item.category', 'item.location', 'item.pickupPoint', 'item.user'])
            ->latest()
            ->paginate(10, ['*'], 'klaim_page')
            ->withQueryString();

        return view('history.index', compact('activeTab', 'myItems', 'myClaims'));
    }
}
