<?php

namespace Tests\Unit;

use App\Services\BridgeAuthenticator;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BridgeAuthenticatorTest extends TestCase
{
    private BridgeAuthenticator $authenticator;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.portal.base_url' => 'https://bridge.uinssc.ac.id/api',
            'services.portal.timeout'  => 5,
        ]);
        $this->authenticator = new BridgeAuthenticator();
    }

    public function test_login_mahasiswa_berhasil_dan_mengambil_profil_lengkap(): void
    {
        Http::fake([
            'https://bridge.uinssc.ac.id/api/v2/portal/login' => Http::response([
                'status'  => true,
                'message' => 'Data Found',
                'count'   => 1,
                'data'    => [
                    'username' => '2281050030',
                    'nama'     => 'LANGIT KALA FAJAR',
                    'role'     => 'mahasiswa',
                    'token'    => 'sample_sanctum_token_123',
                ],
            ], 200),
            'https://bridge.uinssc.ac.id/api/v2/profil' => Http::response([
                'status'  => true,
                'message' => 'Data Found',
                'count'   => 1,
                'data'    => [
                    'nim'                => '2281050030',
                    'nama'               => 'LANGIT KALA FAJAR',
                    'no_hp'              => '081234567890',
                    'fakultas_nama'      => 'ILMU TARBIYAH DAN KEGURUAN',
                    'program_studi_nama' => 'TADRIS MATEMATIKA',
                ],
            ], 200),
        ]);

        $result = $this->authenticator->attempt('2281050030', '123');

        $this->assertNotNull($result);
        $this->assertEquals('2281050030', $result['username']);
        $this->assertEquals('LANGIT KALA FAJAR', $result['nama']);
        $this->assertEquals('mahasiswa', $result['role']);
        $this->assertEquals('ILMU TARBIYAH DAN KEGURUAN', $result['fakultas']);
        $this->assertEquals('TADRIS MATEMATIKA', $result['prodi']);
        $this->assertEquals('081234567890', $result['no_hp']);
    }

    public function test_login_dosen_berhasil_dan_mengambil_profil_dosen(): void
    {
        Http::fake([
            'https://bridge.uinssc.ac.id/api/v2/portal/login' => Http::response([
                'status'  => true,
                'message' => 'Data Found',
                'count'   => 1,
                'data'    => [
                    'username' => '198810112011012019',
                    'nama'     => 'BINTANG HADAR',
                    'role'     => 'dosen',
                    'token'    => 'sample_dosen_token_456',
                ],
            ], 200),
            'https://bridge.uinssc.ac.id/api/v2/profil' => Http::response([
                'status'  => true,
                'message' => 'Data Found',
                'count'   => 1,
                'data'    => [
                    'nip'           => '198810112011012019',
                    'nama'          => 'BINTANG HADAR, S.T., M.Kom.',
                    'no_hp'         => '081299887766',
                    'fakultas'      => 'ILMU TARBIYAH DAN KEGURUAN',
                    'program_studi' => 'S1 - INFORMATIKA',
                ],
            ], 200),
        ]);

        $result = $this->authenticator->attempt('198810112011012019', '123');

        $this->assertNotNull($result);
        $this->assertEquals('198810112011012019', $result['username']);
        $this->assertEquals('BINTANG HADAR, S.T., M.Kom.', $result['nama']);
        $this->assertEquals('dosen', $result['role']);
        $this->assertEquals('ILMU TARBIYAH DAN KEGURUAN', $result['fakultas']);
        $this->assertEquals('S1 - INFORMATIKA', $result['prodi']);
        $this->assertEquals('081299887766', $result['no_hp']);
    }

    public function test_login_gagal_jika_kredensial_salah_atau_404(): void
    {
        Http::fake([
            'https://bridge.uinssc.ac.id/api/v2/portal/login' => Http::response([
                'status'  => false,
                'message' => 'Not Found',
                'count'   => 0,
                'data'    => [],
            ], 404),
        ]);

        $result = $this->authenticator->attempt('9999999999', 'salah');

        $this->assertNull($result);
    }

    public function test_login_tetap_sukses_jika_endpoint_profil_down(): void
    {
        Http::fake([
            'https://bridge.uinssc.ac.id/api/v2/portal/login' => Http::response([
                'status'  => true,
                'message' => 'Data Found',
                'count'   => 1,
                'data'    => [
                    'username' => '2281050030',
                    'nama'     => 'LANGIT KALA FAJAR',
                    'role'     => 'mahasiswa',
                    'token'    => 'sample_token_fallback',
                ],
            ], 200),
            'https://bridge.uinssc.ac.id/api/v2/profil' => Http::response([
                'message' => 'Server Error',
            ], 500),
        ]);

        $result = $this->authenticator->attempt('2281050030', '123');

        $this->assertNotNull($result);
        $this->assertEquals('2281050030', $result['username']);
        $this->assertEquals('LANGIT KALA FAJAR', $result['nama']);
        $this->assertEquals('mahasiswa', $result['role']);
        $this->assertNull($result['fakultas']);
    }

    public function test_login_dummy_admin_berhasil_tanpa_request_eksternal(): void
    {
        Http::preventStrayRequests();

        $result = $this->authenticator->attempt('admin', 'admin123');

        $this->assertNotNull($result);
        $this->assertEquals('admin', $result['username']);
        $this->assertEquals('Administrator Lost & Found', $result['nama']);
        $this->assertEquals('dosen', $result['role']);
        $this->assertTrue($result['is_admin']);
    }
}
