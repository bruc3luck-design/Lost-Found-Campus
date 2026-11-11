<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClaimAndStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $pemilik;
    private User $penklaim1;
    private User $penklaim2;
    private User $admin;
    private Category $kategori;
    private Location $lokasi;
    private Location $pickupPoint;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->pemilik   = User::where('username', '2281050030')->first();
        $this->penklaim1 = User::where('username', '2281050031')->first();
        $this->admin     = User::where('username', '198810112011012019')->first();

        // Buat user tambahan untuk uji multi klaim
        $this->penklaim2 = User::create([
            'username' => '2281050099',
            'name'     => 'MAHASISWA TIGA',
            'role'     => 'mahasiswa',
            'fakultas' => 'SYARIAH',
            'prodi'    => 'HUKUM EKONOMI SYARIAH',
            'no_wa'    => '081999888777',
            'is_admin' => false,
        ]);

        $this->kategori    = Category::first();
        $this->lokasi      = Location::where('is_pickup_point', false)->first();
        $this->pickupPoint = Location::where('is_pickup_point', true)->first();
    }

    public function test_tamu_tidak_dapat_mengakses_form_klaim(): void
    {
        $item = Item::create([
            'user_id'       => $this->pemilik->id,
            'tipe'          => 'hilang',
            'judul'         => 'Item Uji Tamu',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Deskripsi.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        $response = $this->get(route('claims.create', $item));
        $response->assertRedirect('/login');
    }

    public function test_pemilik_tidak_bisa_mengklaim_barangnya_sendiri(): void
    {
        $this->actingAs($this->pemilik);

        $item = Item::create([
            'user_id'       => $this->pemilik->id,
            'tipe'          => 'hilang',
            'judul'         => 'Barang Milik Sendiri',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Deskripsi.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        $response = $this->get(route('claims.create', $item));
        $response->assertRedirect(route('items.show', $item));
        $response->assertSessionHas('error');

        $storeResponse = $this->post(route('claims.store', $item), [
            'bukti' => 'Mencoba klaim barang sendiri dengan bukti.',
        ]);
        $storeResponse->assertRedirect(route('items.show', $item));
        $this->assertDatabaseCount('claims', 0);
    }

    public function test_user_dapat_mengajukan_klaim_dengan_bukti_dan_foto(): void
    {
        Storage::fake('public');
        $this->actingAs($this->penklaim1);

        $item = Item::create([
            'user_id'         => $this->pemilik->id,
            'tipe'            => 'temuan',
            'judul'           => 'Dompet Hitam Ditemukan',
            'category_id'     => $this->kategori->id,
            'location_id'     => $this->lokasi->id,
            'pickup_point_id' => $this->pickupPoint->id,
            'tanggal'         => now()->toDateString(),
            'deskripsi'       => 'Ditemukan di masjid.',
            'status'          => 'ditemukan',
            'status_moderasi' => 'disetujui',
            'disembunyikan'   => false,
        ]);

        $fotoBukti = UploadedFile::fake()->image('bukti_ktm.jpg');

        $response = $this->post(route('claims.store', $item), [
            'bukti'      => 'Di dalam dompet ada kartu tanda mahasiswa atas nama Rindu Senja.',
            'bukti_foto' => $fotoBukti,
        ]);

        $response->assertRedirect(route('my.history', ['tab' => 'klaim']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('claims', [
            'item_id'    => $item->id,
            'claimer_id' => $this->penklaim1->id,
            'status'     => 'menunggu',
        ]);

        $claim = Claim::where('item_id', $item->id)->first();
        $this->assertNotNull($claim->bukti_foto);
        Storage::disk('public')->assertExists($claim->bukti_foto);
    }

    public function test_cegah_klaim_ganda_dari_user_yang_sama(): void
    {
        $this->actingAs($this->penklaim1);

        $item = Item::create([
            'user_id'       => $this->pemilik->id,
            'tipe'          => 'hilang',
            'judul'         => 'Kunci Motor Beat',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Kunci motor hilang.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        // Klaim pertama
        Claim::create([
            'item_id'    => $item->id,
            'claimer_id' => $this->penklaim1->id,
            'bukti'      => 'Bukti klaim pertama.',
            'status'     => 'menunggu',
        ]);

        // Coba buka form klaim lagi
        $respForm = $this->get(route('claims.create', $item));
        $respForm->assertRedirect(route('my.history', ['tab' => 'klaim']));

        // Coba post klaim kedua
        $respPost = $this->post(route('claims.store', $item), [
            'bukti' => 'Mencoba klaim kedua kali.',
        ]);
        $respPost->assertRedirect(route('my.history', ['tab' => 'klaim']));
        $this->assertDatabaseCount('claims', 1);
    }

    public function test_klaim_ditolak_jika_barang_sudah_bukan_status_terbuka(): void
    {
        $this->actingAs($this->penklaim1);

        $item = Item::create([
            'user_id'       => $this->pemilik->id,
            'tipe'          => 'hilang',
            'judul'         => 'Item Sudah Dikembalikan',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Selesai.',
            'status'        => 'dikembalikan',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        $response = $this->get(route('claims.create', $item));
        $response->assertRedirect(route('items.show', $item));
        $response->assertSessionHas('error');
    }

    public function test_pemilik_dapat_melihat_halaman_klaim_masuk(): void
    {
        $this->actingAs($this->pemilik);

        $item = Item::create([
            'user_id'       => $this->pemilik->id,
            'tipe'          => 'hilang',
            'judul'         => 'Tas Laptop Tertinggal',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Deskripsi.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        Claim::create([
            'item_id'    => $item->id,
            'claimer_id' => $this->penklaim1->id,
            'bukti'      => 'Saya menemukannya di kantin lantai 1.',
            'status'     => 'menunggu',
        ]);

        $response = $this->get(route('claims.incoming', $item));
        $response->assertStatus(200);
        $response->assertSee('Saya menemukannya di kantin lantai 1.');
        $response->assertSee($this->penklaim1->name);
        $response->assertSee('Setujui Klaim Ini');
        $response->assertSee('Tolak Klaim');
    }

    public function test_user_lain_tidak_dapat_melihat_klaim_masuk_orang_lain(): void
    {
        $this->actingAs($this->penklaim1);

        $item = Item::create([
            'user_id'       => $this->pemilik->id,
            'tipe'          => 'hilang',
            'judul'         => 'Laporan Pemilik',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Deskripsi.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        $response = $this->get(route('claims.incoming', $item));
        $response->assertForbidden();
    }

    public function test_persetujuan_klaim_dalam_transaksi_menyetujui_satu_klaim_menolak_klaim_lain_dan_mengubah_status_item(): void
    {
        $this->actingAs($this->pemilik);

        $item = Item::create([
            'user_id'       => $this->pemilik->id,
            'tipe'          => 'hilang',
            'judul'         => 'Jaket Parka UIN SSC',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Jaket hilang.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        $klaim1 = Claim::create([
            'item_id'    => $item->id,
            'claimer_id' => $this->penklaim1->id,
            'bukti'      => 'Klaim dari penklaim 1 (valid).',
            'status'     => 'menunggu',
        ]);

        $klaim2 = Claim::create([
            'item_id'    => $item->id,
            'claimer_id' => $this->penklaim2->id,
            'bukti'      => 'Klaim dari penklaim 2 (klaim lain).',
            'status'     => 'menunggu',
        ]);

        // Pemilik menyetujui klaim 1
        $response = $this->post(route('claims.approve', $klaim1), [
            'catatan' => 'Bukti sangat cocok, terima kasih!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Cek bahwa klaim 1 disetujui
        $klaim1->refresh();
        $this->assertEquals('disetujui', $klaim1->status);

        // Cek bahwa klaim 2 otomatis ditolak
        $klaim2->refresh();
        $this->assertEquals('ditolak', $klaim2->status);

        // Cek bahwa status item berubah menjadi 'diklaim'
        $item->refresh();
        $this->assertEquals('diklaim', $item->status);
    }

    public function test_penolakan_klaim_mengubah_status_menjadi_ditolak(): void
    {
        $this->actingAs($this->pemilik);

        $item = Item::create([
            'user_id'       => $this->pemilik->id,
            'tipe'          => 'hilang',
            'judul'         => 'Item Ditawar',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Deskripsi.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        $klaim = Claim::create([
            'item_id'    => $item->id,
            'claimer_id' => $this->penklaim1->id,
            'bukti'      => 'Bukti tidak meyakinkan.',
            'status'     => 'menunggu',
        ]);

        $response = $this->post(route('claims.reject', $klaim), [
            'catatan' => 'Warna tidak cocok dengan barang saya.',
        ]);

        $response->assertRedirect();
        $klaim->refresh();
        $this->assertEquals('ditolak', $klaim->status);
        $this->assertEquals('Warna tidak cocok dengan barang saya.', $klaim->catatan);

        // Status item tetap terbuka ('hilang')
        $item->refresh();
        $this->assertEquals('hilang', $item->status);
    }

    public function test_setelah_klaim_disetujui_kontak_whatsapp_terformat_muncul_di_riwayat(): void
    {
        $item = Item::create([
            'user_id'       => $this->pemilik->id, // no_wa: 08123456789
            'tipe'          => 'hilang',
            'judul'         => 'Helm KYT Putih',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Helm hilang.',
            'status'        => 'diklaim',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        $claim = Claim::create([
            'item_id'    => $item->id,
            'claimer_id' => $this->penklaim1->id, // no_wa: 08123450000
            'bukti'      => 'Saya temukan di parkiran.',
            'status'     => 'disetujui',
        ]);

        // Cek tampilan penklaim (melihat kontak pelapor: wa.me/628123456789)
        $this->actingAs($this->penklaim1);
        $respPenklaim = $this->get(route('my.history', ['tab' => 'klaim']));
        $respPenklaim->assertStatus(200);
        $respPenklaim->assertSee('wa.me/628123456789', false);

        // Cek tampilan pemilik (melihat kontak penklaim: wa.me/628123450000)
        $this->actingAs($this->pemilik);
        $respPemilik = $this->get(route('my.history', ['tab' => 'laporan']));
        $respPemilik->assertStatus(200);
        $respPemilik->assertSee('wa.me/628123450000', false);
    }

    public function test_pemilik_dapat_menandai_barang_telah_dikembalikan(): void
    {
        $this->actingAs($this->pemilik);

        $item = Item::create([
            'user_id'       => $this->pemilik->id,
            'tipe'          => 'hilang',
            'judul'         => 'Barang Siap Diserahkan',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Deskripsi.',
            'status'        => 'diklaim',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        $response = $this->post(route('items.returned', $item));
        $response->assertRedirect();

        $item->refresh();
        $this->assertEquals('dikembalikan', $item->status);
    }
}
