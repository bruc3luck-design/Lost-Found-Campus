<?php

namespace Tests\Feature;

use App\Contracts\PortalAuthenticator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class PortalAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_halaman_login_dapat_ditampilkan(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Lost & Found Campus');
        $response->assertSee('Masuk ke Akun Anda');
        $response->assertSee('NIM / NIP');
    }

    public function test_mahasiswa_1_dapat_login_dengan_akun_portal_dummy(): void
    {
        $response = $this->post('/login', [
            'username' => '2281050030',
            'password' => '123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $user = auth()->user();
        $this->assertEquals('2281050030', $user->username);
        $this->assertEquals('Gamine', $user->name);
        $this->assertEquals('mahasiswa', $user->role);
        $this->assertEquals('08123456789', $user->no_wa);
        $this->assertFalse($user->isAdmin());
    }

    public function test_mahasiswa_2_dapat_login_dengan_akun_portal_dummy(): void
    {
        $response = $this->post('/login', [
            'username' => '2281050031',
            'password' => '123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $user = auth()->user();
        $this->assertEquals('2281050031', $user->username);
        $this->assertEquals('Gamine 2', $user->name);
        $this->assertEquals('mahasiswa', $user->role);
    }

    public function test_dosen_admin_dapat_login_dan_memiliki_status_admin(): void
    {
        $response = $this->post('/login', [
            'username' => '198810112011012019',
            'password' => '123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $user = auth()->user();
        $this->assertEquals('198810112011012019', $user->username);
        $this->assertEquals('Gamine (Dosen Admin)', $user->name);
        $this->assertEquals('dosen', $user->role);
        $this->assertTrue($user->isAdmin());
    }

    public function test_login_gagal_jika_password_salah(): void
    {
        $response = $this->from('/login')->post('/login', [
            'username' => '2281050030',
            'password' => 'password-salah',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_login_gagal_jika_username_tidak_terdaftar(): void
    {
        $response = $this->from('/login')->post('/login', [
            'username' => '9999999999',
            'password' => '123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_user_dapat_logout(): void
    {
        $user = User::where('username', '2281050030')->first();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_admin_dapat_mengakses_panel_filament(): void
    {
        $admin = User::where('username', '198810112011012019')->first();

        $this->assertTrue($admin->canAccessPanel(filament()->getCurrentPanel() ?? filament()->getPanel('admin')));
    }

    public function test_mahasiswa_biasa_ditolak_mengakses_panel_filament(): void
    {
        $user = User::where('username', '2281050030')->first();

        $this->assertFalse($user->canAccessPanel(filament()->getCurrentPanel() ?? filament()->getPanel('admin')));
    }

    public function test_tamu_mengakses_admin_dialihkan_ke_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_fake_authenticator_menolak_lingkungan_production(): void
    {
        $this->app['env'] = 'production';

        $this->expectException(RuntimeException::class);

        $this->app->make(PortalAuthenticator::class);
    }
}
