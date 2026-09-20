# CAT UNSUB (Computer Assisted Test)

CAT UNSUB adalah sebuah sistem ujian berbasis komputer (Computer Assisted Test) komprehensif yang dikembangkan khusus untuk memfasilitasi pelaksanaan ujian seleksi, ujian akademik, maupun simulasi ujian secara digital. 

Sistem ini didesain dengan tingkat keamanan tinggi (*Proctoring*), manajemen waktu absolut (sinkronisasi batas waktu), dan *output* dokumen cetak (*Printables*) interaktif yang mempermudah panitia seleksi, khususnya di Universitas Subang (UNSUB).

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

Aplikasi ini dibangun menggunakan tumpukan web modern dengan performa tinggi:
- **Framework Utama:** [Laravel 13+](https://laravel.com/) (PHP)
- **Frontend & Reaktivitas:** [Livewire 3](https://livewire.laravel.com/) (Untuk navigasi SPA / Single Page Application yang sangat cepat tanpa reload halaman penuh)
- **Styling:** [Tailwind CSS 3](https://tailwindcss.com/) (Menghasilkan UI yang modern, estetik, dan responsif)
- **Interaktivitas Tambahan:** [Alpine.js](https://alpinejs.dev/)
- **Database:** MySQL / MariaDB

---

## 🌟 Fitur Utama (Core Features)

### 👨‍💼 Panel Administrator (Panitia)

**1. Manajemen Ujian Lanjut (Advanced Exam Manager)**
Sistem ini dilengkapi dengan kontrol ujian yang sangat presisi:
- **Sinkronisasi Waktu Absolut (`start_time` & `end_time`):** Ujian diatur berdasarkan zona waktu server secara *real-time*. Jika ujian dijadwalkan selesai pukul 10:00, maka *timer* seluruh peserta akan serentak berhenti pada pukul 10:00 terlepas dari kapan mereka memulai. Hal ini menanggulangi kecurangan eksploitasi perpanjangan waktu lokal.
- **Token Dinamis:** Dilengkapi dengan pembuatan token acak untuk setiap sesi ujian guna memastikan hanya peserta yang berada di ruangan dan menerima token dari pengawas yang dapat mengakses soal.
- **Penentuan Ambang Batas (Passing Grade):** Fitur penentuan standar kelulusan yang secara otomatis melabeli peserta sebagai "Lulus" atau "Tidak Lulus" di akhir sesi.

**2. Manajemen Bank Soal & Kategori**
- Pengelompokan soal berbasis Kategori/Mata Pelajaran (contoh: Pancasila, Pemerintahan Desa).
- Mendukung tipe soal Pilihan Ganda (Multiple Choice) dengan penentuan bobot nilai (poin) yang dinamis pada tiap soal.
- Opsi untuk mengacak urutan soal (*Randomize Questions*) bagi setiap peserta agar meminimalisir saling contek antar peserta di sebelah.

**3. Pemantauan & Kendali Ujian Langsung (Live Monitoring)**
- **Dashboard Real-time:** Memantau status pengerjaan seluruh peserta tanpa perlu memuat ulang halaman (*auto-refresh*). Admin dapat melihat siapa yang *Online*, *Mengerjakan*, atau *Selesai*.
- **Kendali Kedaruratan (Pause/Freeze System):** Inovasi khusus untuk menanggulangi kendala teknis (PC mati, jaringan putus). Admin dapat menekan tombol **Pause** pada peserta tertentu, yang akan membekukan (*freeze*) sisa waktu ujian mereka. Setelah masalah teratasi, Admin dapat menekan **Resume** dan peserta bisa melanjutkan dengan sisa waktu yang dikunci sebelumnya.
- **Force Submit:** Kemampuan Admin untuk memaksa pengumpulan lembar jawaban (*Force Submit*) peserta jika peserta tersebut melanggar tata tertib berat atau enggan menekan selesai.

**4. Interactive Printables (Dokumen Cetak Cerdas)**
Sistem dilengkapi modul pembuatan laporan otomatis berstandar birokrasi pemerintahan:
- **Cetak Kartu Peserta (Participant Cards):** *Generate* nomor ujian dan biodata dalam bentuk kartu identitas yang terstruktur rapi.
- **Daftar Hadir (Attendance Sheet):** Mencetak daftar hadir yang otomatis disesuaikan dengan ujian. Dilengkapi fitur *Smart Prompt*, di mana Admin dapat mengetik nama Pengawas Ruangan secara *pop-up* tepat sebelum mencetak.
- **Berita Acara Ujian (Exam Report):** Pembuatan berita acara resmi secara instan. Menggunakan teknologi *Smart Layouting* (Break-Inside Avoid) yang mencegah terpotongnya area tanda tangan (seperti saksi dan panitia) di antara dua halaman cetak.
- **Form Kejadian Khusus (Incident Report):** *Content-editable* formulir. Pengawas dapat mengetik langsung rincian kejadian khusus, kronologi, dan keputusan panitia ke dalam form *browser* sebelum mencetak, atau mencetak dalam bentuk format bergaris (Lined Paper UI) untuk diisi manual menggunakan pulpen.
- **Rincian Jawaban (Answer Breakdown):** Mencetak riwayat pengerjaan per-peserta secara individual lengkap dengan penanda jawaban yang benar, salah, atau dikosongkan.

### 🧑‍🎓 Panel Peserta (Ujian)

**1. Keamanan & Aksesibilitas (Participant Portal)**
- **Login Praktis:** Metode login dirancang anti-repot, di mana peserta cukup memasukkan Nomer Token (atau Nomor Peserta) tanpa harus menghafal kombinasi sandi (Password) yang rumit.
- **Anti Multi-Login:** Sistem memblokir akses ganda (*Multi-Device Block*) pada ujian yang sama jika status sesi sebelumnya masih aktif berjalan.

**2. Pengawasan Otomatis (Proctoring Module)**
Sistem dilengkapi sensor keamanan berbasis *browser* untuk mencegah kecurangan dasar:
- **Window Blur Detection:** Mendeteksi dan mencatat setiap kali peserta mencoba berpindah *tab* ke mesin pencari atau aplikasi lain.
- **Exit Fullscreen Detection:** Mendeteksi jika peserta dengan sengaja menutup layar penuh (*fullscreen*) selama durasi ujian.
- **Window Resize Detection:** Merekam upaya pengecilan ukuran jendela layar ujian. 
Setiap tindakan di atas akan dicatat sebagai jumlah *Pelanggaran* pada sistem *Live Monitoring* Admin.

**3. Dasbor Pengerjaan Interaktif (Exam Interface)**
- **Navigasi Cepat Tanpa Reload (SPA):** Transisi antar nomor soal terjadi secara instan tanpa perlu memuat ulang (*refresh*) halaman, meminimalisir kemungkinan gagal muat (RTO).
- **Indikator Visual & Ragu-ragu:** Palet warna untuk mengetahui mana soal yang sudah dijawab, mana yang sengaja ditandai ragu-ragu (*Flag for review*), dan mana yang belum tersentuh.
- **Auto-Submit & Sinkronisasi Countdown:** Waktu pengerjaan tampil besar dan tersinkronisasi di sudut layar. Apabila waktu menyentuh angka 00:00:00, ujian otomatis terkunci dan data jawaban tersimpan (*Auto-Submit*).

**4. Mode Simulasi Ujian (Try Out)**
- Mendukung pembuatan "Ujian Simulasi" di mana peserta diizinkan menekan tombol "Kerjakan Ulang" untuk mengulangi sesi ujian berkali-kali sebagai sarana latihan adaptasi sistem CAT (tanpa menghapus riwayat sesi resmi).

---

## 🔀 Alur Sistem (System Flow)

```mermaid
flowchart TD
    %% Administrator Flow
    subgraph Admin ["Panel Administrator"]
        A1["Buat Kategori & Soal"] --> A2["Input Data Peserta & Gelombang"]
        A2 --> A3["Buat Jadwal Ujian"]
        A3 --> A4["Assign Peserta"]
        A4 --> A5["Monitor Live & Pause Control"]
        A5 --> A6["Cetak Berita Acara & Hasil"]
    end

    %% Participant Flow
    subgraph Peserta ["Panel Peserta"]
        P1["Input Token"] --> P2{"Cek Status Ujian"}
        
        P2 -- Belum Waktunya --> P3["Tunggu Waktu (Live)"]
        P2 -- Berlangsung --> P4["Input Token Pengawas"]
        P2 -- Waktu Habis --> Px["Akses Terkunci"]
        
        P4 --> P5{"Proctoring Aktif"}
        P5 --> P6["Kerjakan Soal (Timer Sinkronisasi)"]
        
        P6 -- Pindah Tab / Curang --> P7["Catat Pelanggaran"]
        P6 -- Selesai --> P8["Tampil Skor Akhir"]
    end

    A5 -. Memantau .-> P5
    A5 -. Pause Timer .-> P6
    P8 -. Generate Report .-> A6
```

---

## 🚀 Panduan Instalasi (Development)

Sistem ini membutuhkan perangkat lunak dasar berikut:
- **PHP** (Minimal versi 8.2)
- **Composer** (Package Manager PHP)
- **Node.js & NPM** (Untuk kompilasi frontend TailwindCSS)
- **Database Server** (MySQL/MariaDB) disarankan berjalan dengan aturan Strict Mode.

### Langkah-langkah:
1. **Clone repositori:**
   ```bash
   git clone https://github.com/bagusaliakbar/cat-unsub.git
   cd cat-unsub
   ```

2. **Install Dependensi Backend (Composer):**
   ```bash
   composer install
   ```

3. **Install Dependensi Frontend (NPM):**
   ```bash
   npm install
   npm run build
   ```
   *(Gunakan `npm run dev` jika Anda berencana mengedit file `.blade.php` atau kelas CSS bawaan).*

4. **Konfigurasi Lingkungan (*Environment*):**
   Salin contoh file pengaturan environment:
   ```bash
   cp .env.example .env
   ```
   Edit file `.env` yang baru dibuat dengan menghubungkannya ke Database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_cat_unsub
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate Kunci Enkripsi Aplikasi:**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database & Seeding Data Dummy:**
   Perintah ini akan membuat struktur tabel lengkap beserta satu akun Administrator (`admin@admin.com`) dan beberapa akun Peserta percontohan.
   ```bash
   php artisan migrate --seed
   ```

7. **Jalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Aplikasi siap diakses melalui browser di alamat `http://localhost:8000`.

---

## 🔑 Hak Akses Bawaan (Default Credentials)

**Administrator:**
- **URL Login Admin:** `http://localhost:8000/login`
- **Email:** `admin@admin.com`
- **Password:** `password`

**Peserta Ujian (Simulasi/Testing):**
- **URL Login Peserta:** `http://localhost:8000/` (Halaman Utama)
- **Contoh NIK untuk login:**
  - `P-001` (Budi Santoso)
  - `P-002` (Siti Aminah)
  - `P-003` (Agus Pratama)


