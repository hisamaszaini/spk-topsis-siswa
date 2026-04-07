# Sistem Rekomendasi Lomba Siswa SD (SPK TOPSIS)

Sistem Pendukung Keputusan (SPK) ini dirancang khusus untuk membantu pihak sekolah dalam menentukan rekomendasi lomba yang paling tepat bagi siswa Sekolah Dasar. Sistem ini menggunakan metode **TOPSIS** (*Technique for Order Preference by Similarity to Ideal Solution*) untuk melakukan perankingan berdasarkan kriteria yang telah ditentukan.

## 🚀 Fitur Utama

- **Dashboard**: Ringkasan data kriteria, siswa, dan hasil penilaian.
- **Manajemen Kriteria**: Kelola kriteria penilaian beserta bobot dan jenisnya (Benefit/Cost).
- **Sub-Kriteria**: Penentuan skala nilai untuk setiap kriteria agar penilaian lebih objektif.
- **Data Siswa (Alternatif)**: Manajemen data siswa yang akan dievaluasi.
- **Penilaian**: Input nilai siswa berdasarkan kriteria yang sudah ditetapkan.
- **Perhitungan TOPSIS**: Proses perhitungan matematis yang transparan mulai dari normalisasi hingga nilai preferensi.
- **Hasil Rekomendasi**: Daftar peringkat siswa dengan nilai tertinggi sebagai rekomendasi utama.

## 🛠️ Teknologi yang Digunakan

- **Framework**: Laravel 11
- **Aesthetics**: SB Admin 2 (Bootstrap 4) dengan kustomisasi tema Indigo/Blue.
- **Database**: MySQL / PostgreSQL
- **Iconography**: FontAwesome 5

## 📦 Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan project di lingkungan lokal:

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/spk-topsis-lomba-sd.git
   cd spk-topsis-lomba-sd
   ```

2. **Instal Dependensi PHP**
   ```bash
   composer install
   ```

3. **Instal Dependensi Frontend**
   ```bash
   npm install && npm run build
   ```

4. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Migrasi Database & Seeding**
   ```bash
   php artisan migrate --seed
   ```

6. **Jalankan Server**
   ```bash
   php artisan serve
   ```

## 📖 Cara Penggunaan

1. Login ke sistem.
2. Masukkan data **Kriteria** dan tentukan bobot serta jenisnya.
3. Tambahkan **Sub-Kriteria** untuk setiap kriteria sebagai indikator penilaian.
4. Masukkan data **Siswa** di menu Alternatif.
5. Lakukan **Penilaian** untuk setiap siswa.
6. Lihat hasil perhitungan di menu **Perhitungan** dan hasil akhir di menu **Hasil Akhir**.

---
**SPK Lomba SD** © 2026. Dikembangkan untuk mendukung prestasi siswa sekolah dasar.
