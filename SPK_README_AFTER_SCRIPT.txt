Script selesai membuat file-file inti aplikasi SPK-PMS.

Langkah selanjutnya:
1) Jika belum, install dependencies:
   composer install

2) Copy .env.example ke .env dan atur DB:
   cp .env.example .env
   (ubah DB_DATABASE, DB_USERNAME, DB_PASSWORD sesuai XAMPP)

3) Generate app key:
   php artisan key:generate

4) Migrasi & seed:
   php artisan migrate --seed

5) Serve:
   php artisan serve

Buka http://127.0.0.1:8000
Login: admin@pms.local / password123

Catatan:
- Jika kamu butuh menambahkan logo PDF, ganti bagian <div class="logo">LOGO</div> di resources/views/dashboard/spk/pdf.blade.php
- Untuk styling SB Admin 2 lebih lengkap, kamu bisa tambahkan asset lokal di public/ dan update layout master.blade.php
