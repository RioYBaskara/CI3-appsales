# Aplikasi Manajemen Sales

Aplikasi Manajemen Sales ini dikembangkan untuk memudahkan pengelolaan aktivitas marketing, data closing, dan perjanjian kerjasama (PKS) dari sales. Aplikasi ini juga dilengkapi dengan sistem autentikasi, otorisasi, dan Role-Based Access Control (RBAC) untuk mengatur hak akses pengguna.

## Fitur Utama

1. **Manajemen Aktivitas Marketing**
   - Input dan kelola data aktivitas marketing seperti administrasi, panggilan, atau kunjungan.
   - Simpan informasi nasabah dan status aktivitas (NTB/ETB).
   - Unggah foto terkait aktivitas.

2. **Manajemen Data Closing**
   - Input dan kelola data closing dari sales.
   - Simpan informasi nominal closing dan rekening nasabah.
   - Unggah foto terkait closing.

3. **Manajemen Perjanjian Kerjasama (PKS)**
   - Input dan kelola data perjanjian kerjasama (PKS).
   - Simpan informasi nomor PKS dan tanggal mulai serta akhir PKS.
   - Unggah foto terkait PKS.

4. **Autentikasi dan Otorisasi**
   - Fitur login untuk setiap user, dengan akses yang dibatasi sesuai dengan role yang dimiliki.
   - Fitur pengelolaan user dan penggantian password yang hanya dapat diakses oleh admin.

5. **Role-Based Access Control (RBAC)**
   - Kelola hak akses pengguna berdasarkan role.
   - Dukungan untuk peran dinamis seperti `Administrator`, `Sales`, `Direktur`, dan lainnya.

## Teknologi yang Digunakan

- **Backend**: PHP (CodeIgniter 3)
- **Database**: MySQL/MariaDB
- **Frontend**: HTML, CSS, Bootstrap
- **Autentikasi**: Implementasi custom dengan hashing password

## Instalasi

1. Clone repositori ini ke lokal:
   ```bash
   git clone https://github.com/RioYBaskara/CI3-appsales.git
   ```
2. Import database SQL yang terdapat dalam folder `db/` ke MySQL/MariaDB.
3. Atur konfigurasi database di `application/config/database.php`.
4. Jalankan aplikasi melalui server lokal atau hosting.

## Struktur Database

Aplikasi ini menggunakan beberapa tabel utama:
- `sales`: Menyimpan data sales.
- `nasabah`: Menyimpan data nasabah.
- `aktivitas_marketing`: Menyimpan data aktivitas marketing.
- `closing`: Menyimpan data closing.
- `pks`: Menyimpan data perjanjian kerjasama (PKS).
- `users`: Menyimpan data pengguna aplikasi.
- `user_role`: Menyimpan data role atau jabatan pengguna.
- `user_access_menu`: Menyimpan data akses menu berdasarkan role.
- `user_menu`: Menyimpan data menu yang tersedia.
- `user_sub_menu`: Menyimpan data submenu yang terkait dengan menu utama.

## Penggunaan

Setelah instalasi, admin dapat membuat user baru melalui dashboard admin dan menetapkan role serta hak akses yang sesuai. Setiap sales hanya dapat mengakses data yang terkait dengan dirinya sendiri.

## Kontribusi

Kontribusi sangat terbuka bagi siapa saja yang ingin memperbaiki atau menambah fitur dalam aplikasi ini. Silakan buat pull request atau buka issue baru di GitHub.
