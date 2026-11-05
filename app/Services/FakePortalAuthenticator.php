<?php

namespace App\Services;

use App\Contracts\PortalAuthenticator;

/**
 * Implementasi autentikasi palsu untuk pengujian lokal.
 * Aktif saat PORTAL_DRIVER=fake.
 * JANGAN gunakan di lingkungan produksi!
 */
class FakePortalAuthenticator implements PortalAuthenticator
{
    /**
     * Akun dummy sesuai spesifikasi portal.
     * Password semuanya: "123"
     */
    private array $users = [
        '2281050030' => [
            'username' => '2281050030',
            'nama'     => 'LANGIT KALA FAJAR',
            'role'     => 'mahasiswa',
            'fakultas' => 'ILMU TARBIYAH DAN KEGURUAN',
            'prodi'    => 'TADRIS MATEMATIKA',
            'no_hp'    => '08123456789',
        ],
        '2281050031' => [
            'username' => '2281050031',
            'nama'     => 'RINDU SENJA',
            'role'     => 'mahasiswa',
            'fakultas' => 'SYARIAH',
            'prodi'    => 'HUKUM KELUARGA ISLAM',
            'no_hp'    => '08123450000',
        ],
        '198810112011012019' => [
            'username' => '198810112011012019',
            'nama'     => 'BINTANG HADAR',
            'role'     => 'dosen',
            'fakultas' => 'ILMU TARBIYAH DAN KEGURUAN',
            'prodi'    => 'S1 - INFORMATIKA',
            'no_hp'    => '081299887766',
        ],
    ];

    public function attempt(string $username, string $password): ?array
    {
        if ($password !== '123') {
            return null;
        }

        return $this->users[$username] ?? null;
    }
}
