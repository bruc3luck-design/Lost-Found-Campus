# Lost & Found Campus – UIN Siber Syekh Nurjati Cirebon

Aplikasi web **Lost & Found Campus** resmi untuk civitas akademika UIN Siber Syekh Nurjati Cirebon (UIN SSC). Proyek ini dibangun untuk memudahkan mahasiswa dan dosen melaporkan barang hilang, barang temuan, mengajukan dan memverifikasi klaim, serta mengelola titik pengambilan barang kampus.

---

## 🛠️ Tech Stack
- **Framework:** Laravel 11, PHP 8.2+
- **Database:** MySQL / MariaDB (SQLite untuk Feature Testing)
- **Frontend:** Blade, Tailwind CSS, Alpine.js (Starter kit Laravel Breeze Blade)
- **Admin Panel:** Filament v3
- **Identitas Visual Kampus:** 
  - Hijau UIN (`#0B5D3B` primer, `#08432C` hover/footer)
  - Emas (`#B88830` aksen, `#F6EBD2` light)
  - Ink (`#12261C`), Paper (`#FAFAF6`)

---

## 🚀 Fitur Utama
1. **Lapor Barang Hilang & Temuan:**
   - Unggah foto barang (disimpan di disk `public/storage`).
   - Laporan temuan mewajibkan pemilihan titik pengambilan resmi (Pos Satpam, PUSTIKOM).
2. **Pencarian & Multi-Filter:**
   - Tab navigasi barang Hilang dan Temuan.
   - Filter berdasarkan kata kunci judul/deskripsi, kategori, lokasi, dan rentang tanggal.
3. **Autentikasi Portal Kampus:**
   - Tanpa registrasi publik & tanpa simpan password lokal.
   - Menggunakan sistem interface `PortalAuthenticator` yang siap disambungkan ke API Bridge PUSTIKOM.
4. **Alur Klaim & Verifikasi Transaksional:**
   - Formulir dinamis dan kontekstual (Klaim kepemilikan vs Konfirmasi penemuan).
   - Pencegahan klaim ganda dan klaim atas barang sendiri.
   - Persetujuan klaim dalam `DB::transaction` otomatis mengubah status barang menjadi `diklaim` dan menolak klaim lain yang masih menunggu.
   - Tautan langsung WhatsApp (`wa.me/62...`) terbuka setelah klaim disetujui untuk koordinasi serah terima.
5. **Panel Admin (Filament v3):**
   - Dashboard statistik barang & klaim.
   - Kelola Master Kategori & Lokasi Titik Ambil.
   - Moderasi laporan barang (sembunyikan/tampilkan) & persetujuan klaim.

---

## 🔑 Akun Uji Coba (Dummy Portal)
> **Password untuk semua akun:** `123`

| NIM / NIP | Nama | Peran | Hak Akses |
|---|---|---|---|
| `2281050030` | Gamine | Mahasiswa | Pengguna Publik |
| `2281050031` | Gamine 2 | Mahasiswa | Pengguna Publik |
| `198810112011012019` | Gamine (Dosen Admin) | Dosen | Admin Panel (`/admin`) |

---

## ⚙️ Cara Menjalankan Proyek Secara Lokal

1. **Clone repository:**
   ```bash
   git clone <URL_REPO_GITHUB_ANDA>
   cd lostfound
   ```

2. **Install Dependensi:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Sesuaikan pengaturan database di file `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD).*

4. **Migrasi Database & Seeding:**
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

5. **Build Aset Frontend:**
   ```bash
   npm run build
   ```

6. **Jalankan Development Server:**
   ```bash
   php artisan serve
   ```
   Akses di browser: `http://localhost:8000`  
   Panel Admin: `http://localhost:8000/admin`

7. **Menjalankan Seluruh Pengujian Otomatis (54 Tests):**
   ```bash
   php artisan test
   ```

---
*Dikembangkan untuk Program Magang PUSTIKOM UIN Siber Syekh Nurjati Cirebon.*
