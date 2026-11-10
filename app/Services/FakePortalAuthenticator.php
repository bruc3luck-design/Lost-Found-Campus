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
        'admin' => [
            'username' => 'admin',
            'nama'     => 'Administrator Lost & Found',
            'role'     => 'dosen',
            'fakultas' => 'PUSAT TEKNOLOGI INFORMASI DAN KOMUNIKASI (PUSTIKOM)',
            'prodi'    => 'Pusat Layanan Terpadu',
            'no_hp'    => '081299887766',
            'is_admin' => true,
        ],
        '2281050030' => [
            'username' => '2281050030',
            'nama'     => 'Gamine',
            'role'     => 'mahasiswa',
            'fakultas' => 'ILMU TARBIYAH DAN KEGURUAN',
            'prodi'    => 'TADRIS MATEMATIKA',
            'no_hp'    => '08123456789',
            'is_admin' => false,
        ],
        '2281050031' => [
            'username' => '2281050031',
            'nama'     => 'Gamine 2',
            'role'     => 'mahasiswa',
            'fakultas' => 'SYARIAH',
            'prodi'    => 'HUKUM KELUARGA ISLAM',
            'no_hp'    => '08123450000',
            'is_admin' => false,
        ],
        '198810112011012019' => [
            'username' => '198810112011012019',
            'nama'     => 'Gamine (Dosen Admin)',
            'role'     => 'dosen',
            'fakultas' => 'ILMU TARBIYAH DAN KEGURUAN',
            'prodi'    => 'S1 - INFORMATIKA',
            'no_hp'    => '081299887766',
            'is_admin' => true,
        ],
    ];

    public function attempt(string $username, string $password): ?array
    {
        if ($username === 'admin') {
            if (! in_array($password, ['admin', 'admin123', '123'], true)) {
                return null;
            }
            return $this->users['admin'];
        }

        if ($username === '198810112011012019') {
            if (! in_array($password, ['123', 'admin', 'admin123'], true)) {
                return null;
            }
            return $this->users['198810112011012019'];
        }

        if ($password !== '123') {
            return null;
        }

        return $this->users[$username] ?? null;
    }
}
