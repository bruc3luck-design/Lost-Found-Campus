<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemBrowsingAndFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $mahasiswa1;
    private User $mahasiswa2;
    private User $admin;
    private Category $katDompet;
    private Category $katHP;
    private Location $lokPerpus;
    private Location $lokMasjid;
    private Location $pickupPoint;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->mahasiswa1 = User::where('username', '2281050030')->first();
        $this->mahasiswa2 = User::where('username', '2281050031')->first();
        $this->admin      = User::where('username', '198810112011012019')->first();

        $this->katDompet = Category::where('nama', 'Dompet')->first();
        $this->katHP     = Category::where('nama', 'HP')->first();

        $this->lokPerpus    = Location::where('nama', 'Perpustakaan')->first();
        $this->lokMasjid    = Location::where('nama', 'Masjid Kampus')->first();
        $this->pickupPoint = Location::where('is_pickup_point', true)->first();
    }

    public function test_beranda_dapat_diakses_oleh_publik_tanpa_login(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Lost & Found Campus');
        $response->assertSee('Barang Hilang');
        $response->assertSee('Barang Temuan');
    }

    public function test_beranda_default_menampilkan_tab_barang_hilang(): void
    {
        Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Dompet Hitam Eiger Hilang',
            'category_id'   => $this->katDompet->id,
            'location_id'   => $this->lokPerpus->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Dompet tertinggal di lantai 2.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        Item::create([
            'user_id'         => $this->mahasiswa2->id,
            'tipe'            => 'temuan',
            'judul'           => 'HP Samsung Biru Ditemukan',
            'category_id'     => $this->katHP->id,
            'location_id'     => $this->lokMasjid->id,
            'pickup_point_id' => $this->pickupPoint->id,
            'tanggal'         => now()->toDateString(),
            'deskripsi'       => 'Ditemukan di serambi masjid.',
            'status'          => 'ditemukan',
            'status_moderasi' => 'disetujui',
            'disembunyikan'   => false,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Dompet Hitam Eiger Hilang');
        $response->assertDontSee('HP Samsung Biru Ditemukan');
    }

    public function test_tab_temuan_hanya_menampilkan_barang_temuan(): void
    {
        Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Kunci Motor Beat Hilang',
            'category_id'   => $this->katDompet->id,
            'location_id'   => $this->lokPerpus->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Kunci motor hilang.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        Item::create([
            'user_id'         => $this->mahasiswa2->id,
            'tipe'            => 'temuan',
            'judul'           => 'Tas Ransel Hitam Ditemukan',
            'category_id'     => $this->katDompet->id,
            'location_id'     => $this->lokMasjid->id,
            'pickup_point_id' => $this->pickupPoint->id,
            'tanggal'         => now()->toDateString(),
            'deskripsi'       => 'Tas ransel di serambi masjid.',
            'status'          => 'ditemukan',
            'status_moderasi' => 'disetujui',
            'disembunyikan'   => false,
        ]);

        $response = $this->get('/?tipe=temuan');
        $response->assertStatus(200);
        $response->assertSee('Tas Ransel Hitam Ditemukan');
        $response->assertDontSee('Kunci Motor Beat Hilang');
    }

    public function test_pencarian_kata_kunci_menyaring_judul_dan_deskripsi(): void
    {
        Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Kacamata Rayban Hitam',
            'category_id'   => $this->katDompet->id,
            'location_id'   => $this->lokPerpus->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Kacamata lensa minus tertinggal.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Topi Hitam Polos',
            'category_id'   => $this->katDompet->id,
            'location_id'   => $this->lokPerpus->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Topi tertinggal di meja baca.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        // Cari berdasarkan kata kunci di judul
        $response1 = $this->get('/?tipe=hilang&q=Rayban');
        $response1->assertSee('Kacamata Rayban Hitam');
        $response1->assertDontSee('Topi Hitam Polos');

        // Cari berdasarkan kata kunci di deskripsi
        $response2 = $this->get('/?tipe=hilang&q=lensa');
        $response2->assertSee('Kacamata Rayban Hitam');
        $response2->assertDontSee('Topi Hitam Polos');
    }

    public function test_filter_kategori_dan_lokasi(): void
    {
        Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Barang Dompet di Perpus',
            'category_id'   => $this->katDompet->id,
            'location_id'   => $this->lokPerpus->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Uji filter.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Barang HP di Masjid',
            'category_id'   => $this->katHP->id,
            'location_id'   => $this->lokMasjid->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Uji filter 2.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        // Filter kategori dompet
        $respKat = $this->get('/?tipe=hilang&kategori=' . $this->katDompet->id);
        $respKat->assertSee('Barang Dompet di Perpus');
        $respKat->assertDontSee('Barang HP di Masjid');

        // Filter lokasi masjid
        $respLok = $this->get('/?tipe=hilang&lokasi=' . $this->lokMasjid->id);
        $respLok->assertSee('Barang HP di Masjid');
        $respLok->assertDontSee('Barang Dompet di Perpus');
    }

    public function test_item_yang_disembunyikan_tidak_muncul_di_beranda(): void
    {
        $item = Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Item Rahasia Disembunyikan',
            'category_id'   => $this->katDompet->id,
            'location_id'   => $this->lokPerpus->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Tidak boleh terlihat di beranda.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => true,
        ]);

        $response = $this->get('/?tipe=hilang');
        $response->assertDontSee('Item Rahasia Disembunyikan');
    }

    public function test_detail_barang_dapat_dibuka_oleh_publik(): void
    {
        $item = Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Buku Catatan Kuliah Aljabar',
            'category_id'   => $this->katDompet->id,
            'location_id'   => $this->lokPerpus->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Buku binder warna biru ada stiker UIN SSC.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        $response = $this->get(route('items.show', $item));
        $response->assertStatus(200);
        $response->assertSee('Buku Catatan Kuliah Aljabar');
        $response->assertSee($this->mahasiswa1->name);
        $response->assertSee($this->mahasiswa1->prodi);
        $response->assertSee('Masuk dengan Akun Portal untuk Klaim');
    }

    public function test_detail_barang_menampilkan_tombol_klaim_untuk_non_pemilik(): void
    {
        // Login sebagai mahasiswa 2 (bukan pemilik)
        $this->actingAs($this->mahasiswa2);

        // Barang Hilang -> tombol "Saya Menemukannya"
        $itemHilang = Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'KTM Hilang Milik Mahasiswa 1',
            'category_id'   => $this->katDompet->id,
            'location_id'   => $this->lokPerpus->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Deskripsi KTM.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        $responseHilang = $this->get(route('items.show', $itemHilang));
        $responseHilang->assertStatus(200);
        $responseHilang->assertSee('Saya Menemukannya');

        // Barang Temuan -> tombol "Ini Barang Saya"
        $itemTemuan = Item::create([
            'user_id'         => $this->mahasiswa1->id,
            'tipe'            => 'temuan',
            'judul'           => 'KTM Temuan di Parkiran',
            'category_id'     => $this->katDompet->id,
            'location_id'     => $this->lokPerpus->id,
            'pickup_point_id' => $this->pickupPoint->id,
            'tanggal'         => now()->toDateString(),
            'deskripsi'       => 'Deskripsi temuan.',
            'status'          => 'ditemukan',
            'status_moderasi' => 'disetujui',
            'disembunyikan'   => false,
        ]);

        $responseTemuan = $this->get(route('items.show', $itemTemuan));
        $responseTemuan->assertStatus(200);
        $responseTemuan->assertSee('Ini Barang Saya');
    }

    public function test_detail_barang_menampilkan_opsi_kelola_untuk_pemilik(): void
    {
        $this->actingAs($this->mahasiswa1);

        $item = Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Barang Milik Sendiri',
            'category_id'   => $this->katDompet->id,
            'location_id'   => $this->lokPerpus->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Deskripsi.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => false,
        ]);

        $response = $this->get(route('items.show', $item));
        $response->assertStatus(200);
        $response->assertSee('Ini adalah laporan Anda sendiri');
        $response->assertSee('Edit Laporan');
        $response->assertSee('Hapus');
    }

    public function test_detail_barang_yang_disembunyikan_hanya_dapat_diakses_pemilik_atau_admin(): void
    {
        $item = Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Item Tersembunyi Khusus',
            'category_id'   => $this->katDompet->id,
            'location_id'   => $this->lokPerpus->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Disembunyikan.',
            'status'        => 'hilang',
            'status_moderasi' => 'disetujui',
            'disembunyikan' => true,
        ]);

        // Tamu ditolak (403)
        $respTamu = $this->get(route('items.show', $item));
        $respTamu->assertForbidden();

        // Pengguna lain ditolak (403)
        $this->actingAs($this->mahasiswa2);
        $respLain = $this->get(route('items.show', $item));
        $respLain->assertForbidden();

        // Pemilik diizinkan (200)
        $this->actingAs($this->mahasiswa1);
        $respPemilik = $this->get(route('items.show', $item));
        $respPemilik->assertStatus(200);

        // Admin diizinkan (200)
        $this->actingAs($this->admin);
        $respAdmin = $this->get(route('items.show', $item));
        $respAdmin->assertStatus(200);
    }
}
