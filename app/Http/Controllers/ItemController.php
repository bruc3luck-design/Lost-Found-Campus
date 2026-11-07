<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ItemController extends Controller
{
    /**
     * Tampilkan halaman beranda: daftar barang dengan tab, pencarian, dan filter.
     */
    public function index(Request $request): View
    {
        $tipeAktif = $request->input('tipe', 'hilang');
        if (! in_array($tipeAktif, ['hilang', 'temuan'], true)) {
            $tipeAktif = 'hilang';
        }

        // Query scope berantai dengan eager loading
        $items = Item::query()
            ->tampil()
            ->tipe($tipeAktif)
            ->cari($request->input('q'))
            ->kategori($request->filled('kategori') ? (int) $request->input('kategori') : null)
            ->lokasi($request->filled('lokasi') ? (int) $request->input('lokasi') : null)
            ->antara($request->input('dari'), $request->input('sampai'))
            ->with(['category', 'location', 'pickupPoint', 'user'])
            ->latest('tanggal')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        // Hitung total laporan terbuka untuk badge tab
        $countHilang = Item::tampil()->tipe('hilang')->where('status', 'hilang')->count();
        $countTemuan = Item::tampil()->tipe('temuan')->where('status', 'ditemukan')->count();

        $categories = Category::orderBy('nama')->get();
        $locations  = Location::orderBy('nama')->get();

        return view('home', compact(
            'items',
            'tipeAktif',
            'countHilang',
            'countTemuan',
            'categories',
            'locations'
        ));
    }

    /**
     * Tampilkan detail lengkap barang.
     */
    public function show(Item $item): View
    {
        Gate::authorize('view', $item);

        $item->load(['user', 'category', 'location', 'pickupPoint'])
             ->loadCount('claims');

        return view('items.show', compact('item'));
    }

    /**
     * Tampilkan formulir pelaporan barang baru.
     */
    public function create(Request $request): View
    {
        $categories   = Category::orderBy('nama')->get();
        $locations    = Location::orderBy('nama')->get();
        $pickupPoints = Location::titikPengambilan()->orderBy('nama')->get();

        return view('items.create', compact('categories', 'locations', 'pickupPoints'));
    }

    /**
     * Simpan laporan barang baru ke database.
     */
    public function store(StoreItemRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Upload foto jika disertakan
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('items', 'public');
        }

        // Status awal: hilang -> "hilang"; temuan -> "ditemukan"
        $statusAwal = $validated['tipe'] === 'hilang' ? 'hilang' : 'ditemukan';

        // Titik pengambilan hanya disimpan jika tipe adalah temuan
        $pickupPointId = $validated['tipe'] === 'temuan' ? $validated['pickup_point_id'] : null;

        // Jangan pernah percaya user_id dari request (selalu dari auth()->id())
        $item = Item::create([
            'user_id'         => auth()->id(),
            'tipe'            => $validated['tipe'],
            'judul'           => $validated['judul'],
            'category_id'     => $validated['category_id'],
            'deskripsi'       => $validated['deskripsi'],
            'foto'            => $fotoPath,
            'location_id'     => $validated['location_id'],
            'pickup_point_id' => $pickupPointId,
            'tanggal'         => $validated['tanggal'],
            'status'          => $statusAwal,
            'disembunyikan'   => false,
        ]);

        $pesanTipe = $item->tipe === 'hilang' ? 'kehilangan' : 'penemuan';

        return redirect()
            ->route('my.items')
            ->with('success', "Laporan {$pesanTipe} barang \"{$item->judul}\" berhasil diterbitkan!");
    }

    /**
     * Tampilkan daftar laporan milik pengguna yang sedang login.
     */
    public function myItems(Request $request): View
    {
        $items = auth()->user()
            ->items()
            ->with(['category', 'location', 'pickupPoint'])
            ->withCount('claims')
            ->latest()
            ->paginate(10);

        return view('items.my-items', compact('items'));
    }

    /**
     * Tampilkan formulir edit laporan.
     */
    public function edit(Item $item): View
    {
        Gate::authorize('update', $item);

        $categories   = Category::orderBy('nama')->get();
        $locations    = Location::orderBy('nama')->get();
        $pickupPoints = Location::titikPengambilan()->orderBy('nama')->get();

        return view('items.edit', compact('item', 'categories', 'locations', 'pickupPoints'));
    }

    /**
     * Perbarui data laporan.
     */
    public function update(UpdateItemRequest $request, Item $item): RedirectResponse
    {
        Gate::authorize('update', $item);

        $validated = $request->validated();
        $fotoPath  = $item->foto;

        // Opsi hapus foto lama
        if ($request->boolean('hapus_foto') && $fotoPath) {
            Storage::disk('public')->delete($fotoPath);
            $fotoPath = null;
        }

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            if ($item->foto) {
                Storage::disk('public')->delete($item->foto);
            }
            $fotoPath = $request->file('foto')->store('items', 'public');
        }

        // Titik pengambilan hanya berlaku untuk temuan
        $pickupPointId = $validated['tipe'] === 'temuan' ? $validated['pickup_point_id'] : null;

        $item->update([
            'tipe'            => $validated['tipe'],
            'judul'           => $validated['judul'],
            'category_id'     => $validated['category_id'],
            'deskripsi'       => $validated['deskripsi'],
            'foto'            => $fotoPath,
            'location_id'     => $validated['location_id'],
            'pickup_point_id' => $pickupPointId,
            'tanggal'         => $validated['tanggal'],
        ]);

        return redirect()
            ->route('my.items')
            ->with('success', "Laporan \"{$item->judul}\" berhasil diperbarui!");
    }

    /**
     * Hapus laporan barang.
     */
    public function destroy(Item $item): RedirectResponse
    {
        Gate::authorize('delete', $item);

        // Hapus file foto jika ada
        if ($item->foto) {
            Storage::disk('public')->delete($item->foto);
        }

        $judul = $item->judul;
        $item->delete();

        return redirect()
            ->route('my.items')
            ->with('success', "Laporan \"{$judul}\" telah berhasil dihapus.");
    }

    /**
     * Tandai barang telah berhasil dikembalikan / diserahkan kepada pemilik sah.
     */
    public function markAsReturned(Item $item): RedirectResponse
    {
        Gate::authorize('update', $item);

        if ($item->status === 'dikembalikan') {
            return redirect()
                ->back()
                ->with('info', "Barang \"{$item->judul}\" sudah berstatus Dikembalikan sebelumnya.");
        }

        $item->update([
            'status' => 'dikembalikan',
        ]);

        return redirect()
            ->back()
            ->with('success', "Barang \"{$item->judul}\" berhasil ditandai telah Dikembalikan! Seluruh alur pelaporan telah selesai.");
    }
}
