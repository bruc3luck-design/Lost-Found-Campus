<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use App\Notifications\ItemApprovedNotification;
use App\Notifications\ItemRejectedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ItemModerationTest extends TestCase
{
    use RefreshDatabase;

    private User $mahasiswa;
    private User $mahasiswaLain;
    private User $admin;
    private Category $kategori;
    private Location $lokasi;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->mahasiswa     = User::where('username', '2281050030')->first();
        $this->mahasiswaLain = User::where('username', '2281050031')->first();
        $this->admin         = User::where('username', '198810112011012019')->first();

        $this->kategori = Category::first();
        $this->lokasi   = Location::where('is_pickup_point', false)->first();
    }

    public function test_laporan_baru_memiliki_status_moderasi_menunggu_dan_pesan_yang_sesuai(): void
    {
        $this->actingAs($this->mahasiswa);

        $response = $this->post('/lapor', [
            'tipe'        => 'hilang',
            'judul'       => 'Kalkulator Casio fx-991EX Hilang',
            'category_id' => $this->kategori->id,
            'location_id' => $this->lokasi->id,
            'tanggal'     => now()->toDateString(),
            'deskripsi'   => 'Tertinggal di meja laboratorium siber.',
        ]);

        $response->assertRedirect(route('my.items'));
        $response->assertSessionHas('success');

        $item = Item::where('judul', 'Kalkulator Casio fx-991EX Hilang')->first();
        $this->assertNotNull($item);
        $this->assertEquals('menunggu', $item->status_moderasi);
        $this->assertTrue($item->isPending());
        $this->assertFalse($item->isApproved());
    }

    public function test_laporan_yang_masih_menunggu_tidak_tampil_di_beranda_publik(): void
    {
        $item = Item::create([
            'user_id'         => $this->mahasiswa->id,
            'tipe'            => 'hilang',
            'judul'           => 'Laporan Coba-coba Iseng',
            'category_id'     => $this->kategori->id,
            'location_id'     => $this->lokasi->id,
            'tanggal'         => now()->toDateString(),
            'deskripsi'       => 'Barang coba-coba belum disetujui.',
            'status'          => 'hilang',
            'status_moderasi' => 'menunggu',
            'disembunyikan'   => false,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('Laporan Coba-coba Iseng');
    }

    public function test_laporan_yang_masih_menunggu_dapat_dilihat_oleh_pelapor_di_menu_laporan_saya(): void
    {
        $item = Item::create([
            'user_id'         => $this->mahasiswa->id,
            'tipe'            => 'hilang',
            'judul'           => 'Buku Catatan Algoritma',
            'category_id'     => $this->kategori->id,
            'location_id'     => $this->lokasi->id,
            'tanggal'         => now()->toDateString(),
            'deskripsi'       => 'Buku catatan bersampul biru.',
            'status'          => 'hilang',
            'status_moderasi' => 'menunggu',
            'disembunyikan'   => false,
        ]);

        $this->actingAs($this->mahasiswa);
        $response = $this->get('/laporan-saya');

        $response->assertStatus(200);
        $response->assertSee('Buku Catatan Algoritma');
        $response->assertSee('Menunggu Verifikasi');
    }

    public function test_user_lain_atau_tamu_tidak_dapat_melihat_detail_laporan_yang_belum_disetujui(): void
    {
        $item = Item::create([
            'user_id'         => $this->mahasiswa->id,
            'tipe'            => 'hilang',
            'judul'           => 'Kacamata Minus Hitam',
            'category_id'     => $this->kategori->id,
            'location_id'     => $this->lokasi->id,
            'tanggal'         => now()->toDateString(),
            'deskripsi'       => 'Kacamata di ruang kelas.',
            'status'          => 'hilang',
            'status_moderasi' => 'menunggu',
            'disembunyikan'   => false,
        ]);

        // Tamu (guest)
        $responseGuest = $this->get("/barang/{$item->id}");
        $responseGuest->assertStatus(403);

        // Mahasiswa lain yang bukan pemilik
        $this->actingAs($this->mahasiswaLain);
        $responseLain = $this->get("/barang/{$item->id}");
        $responseLain->assertStatus(403);
    }

    public function test_pemilik_dan_admin_dapat_melihat_detail_laporan_yang_belum_disetujui(): void
    {
        $item = Item::create([
            'user_id'         => $this->mahasiswa->id,
            'tipe'            => 'hilang',
            'judul'           => 'Jam Tangan Casio Hitam',
            'category_id'     => $this->kategori->id,
            'location_id'     => $this->lokasi->id,
            'tanggal'         => now()->toDateString(),
            'deskripsi'       => 'Tertinggal di musala.',
            'status'          => 'hilang',
            'status_moderasi' => 'menunggu',
            'disembunyikan'   => false,
        ]);

        // Pemilik
        $this->actingAs($this->mahasiswa);
        $responseOwner = $this->get("/barang/{$item->id}");
        $responseOwner->assertStatus(200);
        $responseOwner->assertSee('Menunggu Verifikasi Admin');

        // Admin
        $this->actingAs($this->admin);
        $responseAdmin = $this->get("/barang/{$item->id}");
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Menunggu Verifikasi Admin');
    }

    public function test_admin_dapat_menyetujui_laporan_dan_laporan_langsung_tayang(): void
    {
        Notification::fake();

        $item = Item::create([
            'user_id'         => $this->mahasiswa->id,
            'tipe'            => 'hilang',
            'judul'           => 'Kunci Motor Honda Vario',
            'category_id'     => $this->kategori->id,
            'location_id'     => $this->lokasi->id,
            'tanggal'         => now()->toDateString(),
            'deskripsi'       => 'Kunci motor gantungan dompet stnk.',
            'status'          => 'hilang',
            'status_moderasi' => 'menunggu',
            'disembunyikan'   => false,
        ]);

        // Simulasikan persetujuan oleh admin
        $item->update([
            'status_moderasi' => 'disetujui',
            'moderated_at'    => now(),
            'moderated_by'    => $this->admin->id,
        ]);
        $item->user->notify(new ItemApprovedNotification($item));

        // Sekarang harus tayang di publik
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Kunci Motor Honda Vario');

        Notification::assertSentTo($this->mahasiswa, ItemApprovedNotification::class);
    }

    public function test_admin_dapat_menolak_laporan_dengan_catatan(): void
    {
        Notification::fake();

        $item = Item::create([
            'user_id'         => $this->mahasiswa->id,
            'tipe'            => 'hilang',
            'judul'           => 'Laporan Palsu Tidak Jelas',
            'category_id'     => $this->kategori->id,
            'location_id'     => $this->lokasi->id,
            'tanggal'         => now()->toDateString(),
            'deskripsi'       => 'Isi tidak senonoh / tidak pantas.',
            'status'          => 'hilang',
            'status_moderasi' => 'menunggu',
            'disembunyikan'   => false,
        ]);

        // Simulasikan penolakan oleh admin
        $alasan = 'Konten tidak memenuhi etika kampus dan terindikasi palsu.';
        $item->update([
            'status_moderasi'  => 'ditolak',
            'catatan_moderasi' => $alasan,
            'moderated_at'     => now(),
            'moderated_by'     => $this->admin->id,
        ]);
        $item->user->notify(new ItemRejectedNotification($item, $alasan));

        $this->assertEquals('ditolak', $item->fresh()->status_moderasi);
        $this->assertEquals($alasan, $item->fresh()->catatan_moderasi);

        // Tidak boleh tampil di publik
        $response = $this->get('/');
        $response->assertDontSee('Laporan Palsu Tidak Jelas');

        Notification::assertSentTo($this->mahasiswa, ItemRejectedNotification::class);
    }

    public function test_barang_yang_belum_disetujui_tidak_dapat_diklaim(): void
    {
        $item = Item::create([
            'user_id'         => $this->mahasiswa->id,
            'tipe'            => 'temuan',
            'judul'           => 'Uang Tunai 100 Ribu',
            'category_id'     => $this->kategori->id,
            'location_id'     => $this->lokasi->id,
            'tanggal'         => now()->toDateString(),
            'deskripsi'       => 'Ditemukan di lorong kampus.',
            'status'          => 'ditemukan',
            'status_moderasi' => 'menunggu',
            'disembunyikan'   => false,
        ]);

        $this->actingAs($this->mahasiswaLain);

        $responseGet = $this->get("/barang/{$item->id}/klaim");
        $responseGet->assertRedirect(route('home'));
        $responseGet->assertSessionHas('error');

        $responsePost = $this->post("/barang/{$item->id}/klaim", [
            'bukti' => 'Itu uang saya yang jatuh kemarin di depan musala.',
        ]);
        $responsePost->assertRedirect(route('home'));
        $responsePost->assertSessionHas('error');
    }
}
