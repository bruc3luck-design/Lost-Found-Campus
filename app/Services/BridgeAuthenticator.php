<?php

namespace App\Services;

use App\Contracts\PortalAuthenticator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Implementasi autentikasi menggunakan Ecampuz Bridge API Kampus UIN Siber Syekh Nurjati Cirebon.
 * Mengintegrasikan autentikasi portal v2 (/api/v2/portal/login) dan profil pengguna (/api/v2/profil).
 */
class BridgeAuthenticator implements PortalAuthenticator
{
    private string $baseUrl;
    private ?string $bridgeToken;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl     = rtrim(config('services.portal.base_url', 'https://bridge.uinssc.ac.id/api'), '/');
        $this->bridgeToken = config('services.portal.bridge_token', 'informatika-bridge-token');
        $this->timeout     = (int) config('services.portal.timeout', 15);
    }

    /**
     * Memverifikasi kredensial NIM/NIP dan password ke Endpoint Portal Kampus UIN SSC.
     *
     * @return array{username: string, nama: string, role: string, fakultas: string|null, prodi: string|null, no_hp: string|null}|null
     */
    public function attempt(string $username, string $password): ?array
    {
        $normalizedUsername = trim($username);

        // Akun dummy admin untuk pengujian lokal & operasional pengelola
        if ($this->isDummyAdmin($normalizedUsername, $password)) {
            return $this->dummyAdminPayload($normalizedUsername);
        }

        try {
            // 1. Panggil Endpoint Login Portal Kampus v2
            $loginUrl = "{$this->baseUrl}/v2/portal/login";

            $loginResponse = Http::timeout($this->timeout)
                ->acceptJson()
                ->post($loginUrl, [
                    'username' => trim($username),
                    'password' => $password,
                ]);

            // Jika respons bukan HTTP 200 atau login gagal (HTTP 401/404)
            if (! $loginResponse->successful()) {
                Log::info('BridgeAuthenticator: Percobaan login gagal atau kredensial tidak sesuai.', [
                    'username' => $username,
                    'status'   => $loginResponse->status(),
                    'response' => $loginResponse->json(),
                ]);
                return null;
            }

            $loginPayload = $loginResponse->json();

            if (empty($loginPayload['status']) || empty($loginPayload['data'])) {
                Log::warning('BridgeAuthenticator: Status respons bukan true atau data kosong.', [
                    'username' => $username,
                    'payload'  => $loginPayload,
                ]);
                return null;
            }

            $userData = $loginPayload['data'];
            $token    = $userData['token'] ?? null;
            $role     = strtolower((string) ($userData['role'] ?? 'mahasiswa'));

            $fakultas = null;
            $prodi    = null;
            $noHp     = null;
            $nama     = (string) ($userData['nama'] ?? $username);

            // 2. Jika token autentikasi didapat, panggil Endpoint Profil v2 untuk data biodata lengkap
            if (filled($token)) {
                try {
                    $profilUrl = "{$this->baseUrl}/v2/profil";
                    $profilResponse = Http::timeout($this->timeout)
                        ->acceptJson()
                        ->withToken($token)
                        ->get($profilUrl);

                    if ($profilResponse->successful()) {
                        $profilPayload = $profilResponse->json();
                        $profilData    = $profilPayload['data'] ?? [];

                        if (filled($profilData)) {
                            // Nama resmi dari profil
                            if (! empty($profilData['nama'])) {
                                $nama = (string) $profilData['nama'];
                            }

                            // Nomor handphone / WhatsApp untuk koordinasi serah terima barang
                            $noHp = $profilData['no_hp'] ?? $profilData['no_telp'] ?? null;

                            // Fakultas & Prodi berdasarkan role
                            if ($role === 'dosen') {
                                $fakultas = $profilData['fakultas'] ?? null;
                                $prodi    = $profilData['program_studi'] ?? null;
                            } else {
                                $fakultas = $profilData['fakultas_nama'] ?? null;
                                $prodi    = $profilData['program_studi_nama'] ?? null;
                            }
                        }
                    } else {
                        Log::warning('BridgeAuthenticator: Gagal mengambil data profil, menggunakan data dasar login.', [
                            'username' => $username,
                            'status'   => $profilResponse->status(),
                        ]);
                    }
                } catch (Throwable $pe) {
                    Log::warning('BridgeAuthenticator: Exception saat mengambil profil pengguna: ' . $pe->getMessage());
                }
            }

            return [
                'username' => (string) ($userData['username'] ?? $username),
                'nama'     => $nama,
                'role'     => $role,
                'fakultas' => $fakultas,
                'prodi'    => $prodi,
                'no_hp'    => $noHp,
            ];
        } catch (Throwable $e) {
            Log::error('BridgeAuthenticator: Gagal terhubung ke Ecampuz Bridge API UIN SSC: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return null;
        }
    }

    /**
     * Cek apakah kredensial cocok dengan akun dummy administrator.
     */
    private function isDummyAdmin(string $username, string $password): bool
    {
        if ($username !== 'admin') {
            return false;
        }

        $allowedPasswords = ['admin', 'admin123', '123'];

        return in_array($password, $allowedPasswords, true);
    }

    /**
     * Payload untuk akun dummy administrator.
     *
     * @return array{username: string, nama: string, role: string, fakultas: string, prodi: string, no_hp: string, is_admin: bool}
     */
    private function dummyAdminPayload(string $username): array
    {
        return [
            'username' => 'admin',
            'nama'     => 'Administrator Lost & Found',
            'role'     => 'dosen',
            'fakultas' => 'PUSAT TEKNOLOGI INFORMASI DAN KOMUNIKASI (PUSTIKOM)',
            'prodi'    => 'Pusat Layanan Terpadu',
            'no_hp'    => '081299887766',
            'is_admin' => true,
        ];
    }
}
