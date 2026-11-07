<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemReportingTest extends TestCase
{
    use RefreshDatabase;

    private User $mahasiswa1;
    private User $mahasiswa2;
    private User $admin;
    private Category $kategori;
    private Location $lokasi;
    private Location $pickupPoint;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->mahasiswa1 = User::where('username', '2281050030')->first();
        $this->mahasiswa2 = User::where('username', '2281050031')->first();
        $this->admin      = User::where('username', '198810112011012019')->first();

        $this->kategori    = Category::first();
        $this->lokasi      = Location::where('is_pickup_point', false)->first();
        $this->pickupPoint = Location::where('is_pickup_point', true)->first();
    }

    public function test_tamu_tidak_dapat_mengakses_form_lapor_dan_dialihkan_ke_login(): void
    {
        $response = $this->get('/lapor');
        $response->assertRedirect('/login');
    }

    public function test_user_dapat_melihat_form_lapor_barang(): void
    {
        $this->actingAs($this->mahasiswa1);

        $response = $this->get('/lapor');
        $response->assertStatus(200);
        $response->assertSee('Buat Laporan Baru');
        $response->assertSee('Barang Hilang');
        $response->assertSee('Barang Temuan');
        $response->assertSee($this->kategori->nama);
        $response->assertSee($this->pickupPoint->nama);
    }

    public function test_user_dapat_melaporkan_barang_hilang_tanpa_titik_pengambilan(): void
    {
        $this->actingAs($this->mahasiswa1);

        $response = $this->post('/lapor', [
            'tipe'        => 'hilang',
            'judul'       => 'Jaket UIN SSC Hijau Tertinggal',
            'category_id' => $this->kategori->id,
            'location_id' => $this->lokasi->id,
            'tanggal'     => now()->toDateString(),
            'deskripsi'   => 'Jaket almamater ukuran L tertinggal di kursi kantin.',
        ]);

        $response->assertRedirect(route('my.items'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('items', [
            'user_id'         => $this->mahasiswa1->id,
            'tipe'            => 'hilang',
            'judul'           => 'Jaket UIN SSC Hijau Tertinggal',
            'status'          => 'hilang',
            'pickup_point_id' => null,
            'disembunyikan'   => false,
        ]);
    }

    public function test_laporan_temuan_wajib_memilih_titik_pengambilan(): void
    {
        $this->actingAs($this->mahasiswa1);

        $response = $this->post('/lapor', [
            'tipe'        => 'temuan',
            'judul'       => 'Flashdisk Kingston 32GB Hitam',
            'category_id' => $this->kategori->id,
            'location_id' => $this->lokasi->id,
            'tanggal'     => now()->toDateString(),
            'deskripsi'   => 'Ditemukan di lab komputer.',
            // pickup_point_id tidak disertakan
        ]);

        $response->assertSessionHasErrors('pickup_point_id');
        $this->assertDatabaseMissing('items', [
            'judul' => 'Flashdisk Kingston 32GB Hitam',
        ]);
    }

    public function test_user_dapat_melaporkan_barang_temuan_dengan_titik_pengambilan(): void
    {
        $this->actingAs($this->mahasiswa1);

        $response = $this->post('/lapor', [
            'tipe'            => 'temuan',
            'judul'           => 'Kacamata Minus Frame Hitam',
            'category_id'     => $this->kategori->id,
            'location_id'     => $this->lokasi->id,
            'pickup_point_id' => $this->pickupPoint->id,
            'tanggal'         => now()->toDateString(),
            'deskripsi'       => 'Kacamata ditemukan di rak sepatu masjid kampus.',
        ]);

        $response->assertRedirect(route('my.items'));

        $this->assertDatabaseHas('items', [
            'user_id'         => $this->mahasiswa1->id,
            'tipe'            => 'temuan',
            'judul'           => 'Kacamata Minus Frame Hitam',
            'status'          => 'ditemukan',
            'pickup_point_id' => $this->pickupPoint->id,
        ]);
    }

    public function test_tanggal_kejadian_tidak_boleh_masa_depan(): void
    {
        $this->actingAs($this->mahasiswa1);

        $response = $this->post('/lapor', [
            'tipe'        => 'hilang',
            'judul'       => 'Barang Masa Depan',
            'category_id' => $this->kategori->id,
            'location_id' => $this->lokasi->id,
            'tanggal'     => now()->addDays(2)->toDateString(),
            'deskripsi'   => 'Uji coba tanggal masa depan.',
        ]);

        $response->assertSessionHasErrors('tanggal');
    }

    public function test_upload_foto_berhasil_disimpan_ke_storage_public(): void
    {
        Storage::fake('public');
        $this->actingAs($this->mahasiswa1);

        $file = UploadedFile::fake()->image('dompet.jpg', 600, 600)->size(1024);

        $response = $this->post('/lapor', [
            'tipe'        => 'hilang',
            'judul'       => 'Dompet Kulit Cokelat Ada Foto',
            'category_id' => $this->kategori->id,
            'location_id' => $this->lokasi->id,
            'tanggal'     => now()->toDateString(),
            'deskripsi'   => 'Dompet berisi kartu identitas.',
            'foto'        => $file,
        ]);

        $response->assertRedirect(route('my.items'));

        $item = Item::where('judul', 'Dompet Kulit Cokelat Ada Foto')->first();
        $this->assertNotNull($item);
        $this->assertNotNull($item->foto);

        Storage::disk('public')->assertExists($item->foto);
    }

    public function test_user_dapat_melihat_daftar_laporannya_di_halaman_laporan_saya(): void
    {
        $this->actingAs($this->mahasiswa1);

        $item = Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Item Spesifik Mahasiswa 1',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Deskripsi uji coba.',
            'status'        => 'hilang',
            'disembunyikan' => false,
        ]);

        $response = $this->get('/laporan-saya');
        $response->assertStatus(200);
        $response->assertSee('Item Spesifik Mahasiswa 1');
    }

    public function test_user_dapat_mengedit_laporannya_sendiri(): void
    {
        $this->actingAs($this->mahasiswa1);

        $item = Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Judul Sebelum Edit',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Deskripsi lama.',
            'status'        => 'hilang',
            'disembunyikan' => false,
        ]);

        $response = $this->get(route('items.edit', $item));
        $response->assertStatus(200);
        $response->assertSee('Judul Sebelum Edit');

        $updateResponse = $this->put(route('items.update', $item), [
            'tipe'        => 'hilang',
            'judul'       => 'Judul Sesudah Diedit',
            'category_id' => $this->kategori->id,
            'location_id' => $this->lokasi->id,
            'tanggal'     => now()->toDateString(),
            'deskripsi'   => 'Deskripsi yang telah diperbarui.',
        ]);

        $updateResponse->assertRedirect(route('my.items'));
        $this->assertDatabaseHas('items', [
            'id'    => $item->id,
            'judul' => 'Judul Sesudah Diedit',
        ]);
    }

    public function test_user_lain_ditolak_mengedit_laporan_orang_lain(): void
    {
        $this->actingAs($this->mahasiswa2);

        $item = Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Milik Mahasiswa 1',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Deskripsi item.',
            'status'        => 'hilang',
            'disembunyikan' => false,
        ]);

        // Mencoba akses form edit
        $response = $this->get(route('items.edit', $item));
        $response->assertForbidden();

        // Mencoba kirim request update
        $updateResponse = $this->put(route('items.update', $item), [
            'tipe'        => 'hilang',
            'judul'       => 'Mencoba Bajak Judul',
            'category_id' => $this->kategori->id,
            'location_id' => $this->lokasi->id,
            'tanggal'     => now()->toDateString(),
            'deskripsi'   => 'Mencoba ganti deskripsi.',
        ]);
        $updateResponse->assertForbidden();
    }

    public function test_user_lain_ditolak_menghapus_laporan_orang_lain(): void
    {
        $this->actingAs($this->mahasiswa2);

        $item = Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Item Terlindungi',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Tidak boleh dihapus orang lain.',
            'status'        => 'hilang',
            'disembunyikan' => false,
        ]);

        $response = $this->delete(route('items.destroy', $item));
        $response->assertForbidden();

        $this->assertDatabaseHas('items', ['id' => $item->id]);
    }

    public function test_admin_dapat_mengedit_dan_menghapus_laporan_siapapun(): void
    {
        $this->actingAs($this->admin);

        $item = Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Item Dibuat Mahasiswa',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Admin berhak memoderasi.',
            'status'        => 'hilang',
            'disembunyikan' => false,
        ]);

        // Admin dapat melihat form edit
        $response = $this->get(route('items.edit', $item));
        $response->assertStatus(200);

        // Admin dapat menghapus
        $delResponse = $this->delete(route('items.destroy', $item));
        $delResponse->assertRedirect(route('my.items'));

        $this->assertSoftDeleted('items', ['id' => $item->id]);
    }

    public function test_menghapus_laporan_juga_menghapus_file_foto_dari_storage(): void
    {
        Storage::fake('public');
        $this->actingAs($this->mahasiswa1);

        $file = UploadedFile::fake()->image('foto_hapus.jpg');
        $path = $file->store('items', 'public');

        $item = Item::create([
            'user_id'       => $this->mahasiswa1->id,
            'tipe'          => 'hilang',
            'judul'         => 'Item dengan Foto untuk Dihapus',
            'category_id'   => $this->kategori->id,
            'location_id'   => $this->lokasi->id,
            'tanggal'       => now()->toDateString(),
            'deskripsi'     => 'Foto harus ikut terhapus.',
            'foto'          => $path,
            'status'        => 'hilang',
            'disembunyikan' => false,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->delete(route('items.destroy', $item));
        $response->assertRedirect(route('my.items'));

        Storage::disk('public')->assertMissing($path);
        $this->assertSoftDeleted('items', ['id' => $item->id]);
    }
}
