<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'username' => '198810112011012019',
            'name' => 'BINTANG HADAR',
            'role' => 'dosen',
            'fakultas' => 'ILMU TARBIYAH DAN KEGURUAN',
            'prodi' => 'S1 - INFORMATIKA',
            'no_wa' => '081299887766',
            'is_admin' => true,
        ]);

        $this->regularUser = User::create([
            'username' => '2281050030',
            'name' => 'LANGIT KALA FAJAR',
            'role' => 'mahasiswa',
            'fakultas' => 'ILMU TARBIYAH DAN KEGURUAN',
            'prodi' => 'TADRIS MATEMATIKA',
            'no_wa' => '08123456789',
            'is_admin' => false,
        ]);
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_user_can_access_admin_panel_dashboard(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Lost & Found Campus UIN SSC');
    }

    public function test_admin_can_view_category_resource_list(): void
    {
        Category::create(['nama' => 'Elektronik']);

        $response = $this->actingAs($this->adminUser)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('Elektronik');
    }

    public function test_admin_can_view_location_resource_list(): void
    {
        Location::create([
            'nama' => 'Pos Satpam Utama',
            'is_pickup_point' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/locations');
        $response->assertStatus(200);
        $response->assertSee('Pos Satpam Utama');
    }

    public function test_admin_can_view_items_resource_list(): void
    {
        $category = Category::create(['nama' => 'Kunci']);
        $location = Location::create(['nama' => 'Parkiran']);

        Item::create([
            'user_id' => $this->regularUser->id,
            'tipe' => 'hilang',
            'judul' => 'Kunci Motor Vario Hitam',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'tanggal' => now()->subDay()->toDateString(),
            'status' => 'hilang',
            'deskripsi' => 'Gantungan kunci logo UIN.',
            'disembunyikan' => false,
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/items');
        $response->assertStatus(200);
        $response->assertSee('Kunci Motor Vario Hitam');
    }

    public function test_admin_can_view_claims_resource_list(): void
    {
        $category = Category::create(['nama' => 'Tas']);
        $location = Location::create(['nama' => 'Perpustakaan']);

        $item = Item::create([
            'user_id' => $this->adminUser->id,
            'tipe' => 'temuan',
            'judul' => 'Tas Ransel Abu-abu',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'tanggal' => now()->toDateString(),
            'status' => 'ditemukan',
            'deskripsi' => 'Ditemukan di meja baca lantai 2.',
            'disembunyikan' => false,
        ]);

        Claim::create([
            'item_id' => $item->id,
            'claimer_id' => $this->regularUser->id,
            'bukti' => 'Ada stiker nama saya di saku depan.',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/claims');
        $response->assertStatus(200);
        $response->assertSee('Tas Ransel Abu-abu');
        $response->assertSee('LANGIT KALA FAJAR');
    }
}
