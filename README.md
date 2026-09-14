# CAT UNSUB (Computer Assisted Test)

CAT UNSUB adalah sebuah sistem ujian berbasis komputer (Computer Assisted Test) yang dikembangkan untuk memfasilitasi pelaksanaan ujian, seleksi, maupun simulasi ujian secara digital. Sistem ini dirancang dengan antarmuka yang modern, dinamis, dan mudah digunakan baik oleh administrator (panitia ujian) maupun peserta.

---

## 🚀 Teknologi yang Digunakan (Tech Stack)

Aplikasi ini dibangun menggunakan teknologi web modern:
- **Framework Utama:** [Laravel 13](https://laravel.com/) (PHP)
- **Frontend & Reaktivitas:** [Livewire 3](https://livewire.laravel.com/) (Mendukung navigasi SPA/Single Page Application yang cepat)
- **Styling:** [Tailwind CSS 3](https://tailwindcss.com/) (Menghasilkan UI yang modern dan responsif)
- **Interaktivitas Tambahan:** [Alpine.js](https://alpinejs.dev/)
- **Database:** MySQL / SQLite (Sesuai konfigurasi `.env`)

---

## 🌟 Fitur Utama

### 👑 Panel Administrator (Panitia)
- **Manajemen Bank Soal:** Membuat, mengubah, dan menghapus soal (Pilihan Ganda & Essay) lengkap dengan penentuan bobot dan kategori soal.
- **Manajemen Ujian:** Membuat sesi ujian, menentukan durasi, nilai kelulusan (passing grade), waktu mulai/selesai, fitur acak soal/jawaban, dan dukungan Token Ujian.
- **Manajemen Gelombang (Waves):** Mengelompokkan peserta ke dalam gelombang (shift) ujian tertentu.
- **Manajemen Peserta:** Mengelola data biodata peserta secara detail (NIK, Nama, Nomor Peserta, dll).
- **Sistem *Assign* Peserta:** Mendaftarkan peserta (individu atau berdasarkan gelombang) ke ujian tertentu secara massal.
- **Monitor Ujian (Real-time):** Memantau progres pengerjaan peserta secara langsung, mencetak daftar hadir, mencetak rekapitulasi nilai, serta fitur **Jeda Ujian Darurat (Pause/Resume)** jika terjadi kendala teknis pada peserta tertentu.
- **Cetak Laporan & Berita Acara:** Men- *generate* laporan akhir ujian, detail sesi peserta, beserta berita acara untuk ditandatangani.
- **Log Pelanggaran (Proctoring Sederhana):** Mencatat peringatan jika peserta mencoba keluar dari layar ujian (*tab-switching*).

### 🎓 Panel Peserta (Peserta Ujian)
- **Login Sederhana:** Peserta cukup masuk menggunakan NIK atau Nomor Peserta (Tanpa perlu mengingat *password* rumit pada skenario *default*).
- **Dashboard Ringkas:** Menampilkan daftar ujian yang sedang aktif dan di-*assign* kepadanya. Menampilkan tombol pintar yang berubah sesuai status waktu ("Belum Dimulai", "Mulai Kerjakan", "Lanjutkan", "Waktu Habis").
- **Validasi Token Ujian:** Mewajibkan peserta memasukkan Token yang diberikan pengawas sebelum memulai.
- **Ujian Simulasi:** Fitur khusus "Kerjakan Ulang" yang mengizinkan peserta mengulang soal berkali-kali jika ujian tersebut ditandai sebagai Ujian Simulasi.
- **Halaman Pengerjaan Interaktif:** 
  - Navigasi soal yang mulus (tanpa *reload* halaman).
  - Waktu hitung mundur (*countdown timer*) yang presisi dan otomatis memotong waktu jika peserta terlambat (disesuaikan dengan batas `end_time` ujian).
  - Indikator soal terjawab, belum terjawab, dan ragu-ragu.
- **Hasil Instan:** Menampilkan skor dan status kelulusan segera setelah ujian selesai (jika diizinkan admin).

---

## 🔄 Alur Sistem (System Flow)

### 1. Persiapan oleh Administrator
1. **Buat Kategori Soal** (Opsional) untuk mengelompokkan soal.
2. **Buat Soal di Bank Soal:** Admin memasukkan soal, opsi jawaban (untuk pilihan ganda), menentukan kunci jawaban, dan bobot poin.
3. **Buat Gelombang (Wave):** (Opsional) Jika peserta akan dibagi ke dalam beberapa sesi/ruangan.
4. **Input Data Peserta:** Admin memasukkan data peserta lengkap dengan NIK yang akan digunakan sebagai identitas *login*.
5. **Buat Ujian Baru:** Admin mengatur nama ujian, durasi, *passing grade*, token, serta mengaktifkan mode acak (opsional).
6. **Pilih Soal untuk Ujian:** Admin mengambil soal-soal dari Bank Soal ke dalam ujian yang baru dibuat (dilengkapi dengan filter pencarian dan jenis soal).
7. **Assign Peserta:** Admin menugaskan ujian tersebut kepada peserta yang berhak (berdasarkan Gelombang atau dipilih satu-per-satu).
8. **Aktifkan Ujian:** Mengubah status ujian menjadi *Aktif* agar muncul di *dashboard* peserta.

### 2. Pelaksanaan oleh Peserta
1. **Masuk (Login):** Peserta membuka halaman utama dan memasukkan **NIK** (atau nomor peserta) lalu menekan tombol validasi.
2. **Pilih Ujian:** Peserta melihat daftar ujian yang tersedia. 
   - Jika belum waktunya, tombol akan terkunci ("Belum Dimulai").
   - Jika sudah waktunya, peserta menekan "Mulai Kerjakan".
3. **Input Token:** Jika ujian tersebut diamankan oleh Token, sistem akan memunculkan *popup* Tata Tertib yang meminta input Token Ujian.
4. **Pengerjaan Soal:** 
   - Peserta menjawab soal-soal yang ada. 
   - Jika peserta mencoba keluar dari mode *fullscreen* atau pindah tab, sistem akan mencatat pelanggaran.
   - Waktu terus berjalan mundur, dan jawaban akan tersimpan otomatis.
5. **Selesai:** Peserta menekan tombol "Selesai" atau sistem akan otomatis menutup saat waktu habis. Peserta akan langsung melihat skor mereka (Lulus/Tidak Lulus).

### 3. Monitoring & Pelaporan (Pasca Ujian)
1. **Monitor Langsung:** Selama ujian berlangsung, Admin dapat memantau dari menu *Monitor Ujian* untuk melihat siapa yang sedang mengerjakan, berapa poin sementaranya, dan apakah ada yang melakukan pelanggaran.
2. **Jeda/Pause (Emergency):** Jika PC peserta mati atau jaringannya bermasalah, Admin dapat mem-*pause* ujian peserta tersebut agar waktunya berhenti (tersimpan). Setelah masalah teratasi, Admin melakukan *resume* dan peserta dapat melanjutkan dengan sisa waktu yang utuh.
3. **Cetak Hasil:** Admin dapat mencetak Daftar Hadir, Rekapitulasi Nilai Akhir, dan Berita Acara Ujian (yang telah terisi formalnya seperti nama pengawas, nip, dsb) langsung dalam format yang rapi untuk dicetak.

---

## 🛠️ Panduan Instalasi (Development)

Pastikan sistem Anda sudah terinstal:
- PHP (minimal versi 8.2)
- Composer
- Node.js & NPM
- Database Server (MySQL/MariaDB)

### Langkah Instalasi:
1. **Clone repositori:**
   ```bash
   git clone https://github.com/bagusaliakbar/cat-unsub.git
   cd cat-unsub
   ```

2. **Install Dependensi PHP (Composer):**
   ```bash
   composer install
   ```

3. **Install Dependensi Node.js (NPM):**
   ```bash
   npm install
   npm run build
   ```
   *(Gunakan `npm run dev` jika Anda ingin mengembangkan fitur UI).*

4. **Konfigurasi Lingkungan (*Environment*):**
   Duplikat file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan atur koneksi *database* Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate *Application Key*:**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi dan *Seeding* Database:**
   *(Seeder bawaan akan membuat 1 akun Admin `admin@admin.com` dengan password `password` dan beberapa user dummy).*
   ```bash
   php artisan migrate --seed
   ```

7. **Jalankan *Local Server*:**
   ```bash
   php artisan serve
   ```
   Aplikasi sekarang dapat diakses melalui `http://localhost:8000`.

---

## 🔒 Akses Default

Setelah instalasi dan seeding, Anda dapat masuk dengan akun berikut:

**Administrator:**
- **URL Login Admin:** `http://localhost:8000/login`
- **Email:** `admin@admin.com`
- **Password:** `password`

**Peserta Ujian (Dummy):**
- **URL Akses Peserta:** `http://localhost:8000/` (Halaman Utama)
- **NIK Dummy (Pilih salah satu):**
  - `3201010101900001` (Budi Santoso)
  - `3201010202920002` (Siti Aminah)
  - `3201010303930003` (Agus Pratama)

---

> *Dokumentasi ini ditulis untuk memberikan gambaran arsitektur serta instruksi dasar bagi pengembang atau administrator yang akan menggunakan/memodifikasi sistem CAT UNSUB.*
