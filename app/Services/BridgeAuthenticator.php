<?php

namespace App\Services;

use App\Contracts\PortalAuthenticator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Implementasi autentikasi menggunakan API Portal/SSO Kampus UIN SSC.
 * Digunakan ketika PORTAL_DRIVER=bridge di lingkungan staging atau production.
 */
class BridgeAuthenticator implements PortalAuthenticator
{
    private ?string $apiUrl;
    private ?string $apiSecret;
    private int $timeout;

    public function __construct()
    {
        $this->apiUrl    = config('services.portal.bridge_url');
        $this->apiSecret = config('services.portal.bridge_secret');
        $this->timeout   = (int) config('services.portal.timeout', 10);
    }

    /**
     * Memverifikasi kredensial NIM/NIP dan password ke Endpoint Portal Kampus UIN SSC.
     *
     * @return array{username: string, nama: string, role: string, fakultas: string|null, prodi: string|null, no_hp: string|null}|null
     */
    public function attempt(string $username, string $password): ?array
    {
        if (blank($this->apiUrl)) {
            Log::error('BridgeAuthenticator: PORTAL_BRIDGE_URL belum dikonfigurasi di file .env.');
            return null;
        }

        try {
            $request = Http::timeout($this->timeout)
                ->acceptJson();

            // Jika API kampus membutuhkan Header API Key / Secret Token
            if (filled($this->apiSecret)) {
                $request->withToken($this->apiSecret);
            }

            $response = $request->post($this->apiUrl, [
                'username' => $username,
                'password' => $password,
            ]);

            if (! $response->successful()) {
                Log::warning('BridgeAuthenticator: Autentikasi gagal atau API merespons dengan status non-200.', [
                    'username' => $username,
                    'status'   => $response->status(),
                ]);
                return null;
            }

            $payload = $response->json();

            // Validasi format kembalian dari API kampus
            // Mendukung baik struktur { data: { ... } } maupun flat { username: ... }
            $userData = $payload['data'] ?? $payload;

            if (empty($userData['username']) || empty($userData['nama']) || empty($userData['role'])) {
                Log::error('BridgeAuthenticator: Respons API kampus tidak memiliki atribut wajib (username, nama, role).', [
                    'payload' => $payload,
                ]);
                return null;
            }

            return [
                'username' => (string) $userData['username'],
                'nama'     => (string) $userData['nama'],
                'role'     => strtolower((string) $userData['role']),
                'fakultas' => $userData['fakultas'] ?? null,
                'prodi'    => $userData['prodi'] ?? null,
                'no_hp'    => $userData['no_hp'] ?? $userData['phone'] ?? null,
            ];
        } catch (Throwable $e) {
            Log::error('BridgeAuthenticator: Gagal terhubung ke API Portal Kampus: '.$e->getMessage(), [
                'exception' => $e,
            ]);
            return null;
        }
    }
}
