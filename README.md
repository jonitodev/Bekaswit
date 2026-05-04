# Bekaswit - Bekas Jadi Duit

Platform marketplace lokal berbasis web untuk jual beli barang bekas antar penghuni kos mahasiswa di Malang Kota.

## Deskripsi

Bekaswit adalah platform yang memudahkan mahasiswa penghuni kos di Malang untuk menjual dan membeli barang bekas kebutuhan kos. Dilengkapi fitur filter berdasarkan area kecamatan, kategori barang, dan integrasi langsung ke WhatsApp untuk komunikasi antara penjual dan pembeli. Transaksi dilakukan secara COD (Cash on Delivery).

## Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL 8
- **Frontend:** Blade Templates + Bootstrap 5
- **Transaksi:** COD Only (tanpa payment gateway)

## Cara Instalasi

```bash
# 1. Clone repository
git clone https://github.com/your-repo/bekaswit.git
cd bekaswit

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Buat database MySQL
# Buat database dengan nama 'bekaswit' di MySQL

# 6. Sesuaikan konfigurasi database di .env
# DB_DATABASE=bekaswit
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Jalankan migration
php artisan migrate

# 8. Jalankan seeder
php artisan db:seed

# 9. Buat symbolic link untuk storage
php artisan storage:link

# 10. Jalankan server
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## Akun Dummy untuk Testing

| Nama            | Email              | Password   | Area           |
|-----------------|--------------------|------------|----------------|
| Andi Pratama    | andi@example.com   | password   | Lowokwaru      |
| Budi Santoso    | budi@example.com   | password   | Klojen         |
| Citra Dewi      | citra@example.com  | password   | Blimbing       |
| Dani Setiawan   | dani@example.com   | password   | Sukun          |
| Eka Putri       | eka@example.com    | password   | Kedungkandang  |

## Fitur Utama

- Posting barang bekas dengan foto (maks. 4 foto)
- Filter berdasarkan area kecamatan dan kategori barang
- Pencarian barang dengan keyword
- Sorting berdasarkan harga dan tanggal posting
- Status barang: Tersedia, Booking, Terjual
- Integrasi WhatsApp untuk menghubungi penjual
- Manajemen listing barang pribadi
- Profil pengguna dengan area kecamatan

## Area Cakupan

Khusus kecamatan di Malang Kota:
1. Lowokwaru
2. Klojen
3. Blimbing
4. Sukun
5. Kedungkandang

## Kategori Barang

1. Alat Masak
2. Furniture
3. Elektronik
4. Perlengkapan Kamar

## Anggota Kelompok

| No | Nama                              | NIM            | Role                    |
|----|-----------------------------------|----------------|-------------------------|
| 1  | Silva Tria Alfares                | 254107023001   | Backend & Infrastructure|
| 2  | Izza Dhafira Fanani               | 244107020106   | Authentication Dev      |
| 3  | Gilang Bayu Irwana                | 244107020194   | Frontend Dev            |
| 4  | Joni Yoga Kusuma                  | 254107023003   | Core Feature Dev        |
| 5  | Mochamad Yunan Helmy Affandi      | 244107020101   | Search & Integration    |

## Institusi

Politeknik Negeri Malang - 2026
