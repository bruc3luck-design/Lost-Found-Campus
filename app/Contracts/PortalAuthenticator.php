<?php

namespace App\Contracts;

interface PortalAuthenticator
{
    /**
     * Mencoba autentikasi pengguna via portal kampus.
     *
     * @return array{username: string, nama: string, role: string, fakultas: string|null, prodi: string|null, no_hp: string|null}|null
     */
    public function attempt(string $username, string $password): ?array;
}
