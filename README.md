# 📱 Sistem Rekap Penyerahan & Pengembalian Gawai

Aplikasi berbasis web (Dashboard Admin) yang digunakan untuk mencatat dan mengelola rekapitulasi data penyerahan dan pengembalian gawai (HP/Perangkat) secara *real-time* tanpa *reload* halaman (*Single Page Application feel*).

Sistem ini didesain agar sangat mudah digunakan, responsif, dan memiliki fitur *auto-setup* (database dan tabel akan otomatis terbuat saat aplikasi pertama kali dijalankan).

---

## ✨ Fitur Utama

- **⚡ Proses Cepat (AJAX):** Semua proses Tambah, Edit, Hapus, dan Pencarian data dilakukan di dalam pop-up *modal* tanpa perlu *refresh* (pindah halaman).
- **🤖 Auto-Fill Tanggal & Waktu:** Saat menambahkan data baru, form akan secara otomatis mendeteksi Hari, Tanggal, dan Jam saat itu juga.
- **📊 Dashboard Statistik:** Menampilkan ringkasan total data, total gawai diserahkan, dikembalikan, serta selisih gawai yang belum dikembalikan.
- **🔍 Pencarian & Filter:** Cari data berdasarkan nama petugas/guru, dan saring data berdasarkan rentang tanggal (Dari - Sampai).
- **🖨️ Cetak Laporan (Format Excel):** Laporan dapat dicetak dengan format tabel terstruktur, warna *header* khusus, ringkasan selisih data, dan kolom tanda tangan (siap cetak PDF/Printer).
- **⚙️ Auto-Setup Database:** Tidak perlu repot impor file `.sql`. Sistem akan otomatis membuat database `rekap_yuu` dan tabel `rekap_gawai` jika belum ada.

---

## 🛠️ Teknologi yang Digunakan

- **Frontend:** HTML5, CSS3 (Custom Styles), JavaScript (Vanilla / Fetch API)
- **Backend:** PHP Native (Versi 7.4 / 8.x)
- **Database:** MySQL / MariaDB (via PDO & MySQLi)
- **Font & Ikon:** Inter (Google Fonts), SVG Icons

---

## 🚀 Cara Instalasi & Penggunaan

1. **Persiapan Server Lokal:**
   Pastikan Anda sudah menginstal aplikasi web server seperti [XAMPP](https://www.apachefriends.org/index.html), WAMP, atau Laragon.
   Jalankan modul **Apache** dan **MySQL**.

2. **Clone atau Download Repositori:**
   - Download repositori ini sebagai ZIP dan ekstrak.
   - Atau *clone* menggunakan Git:
     ```bash
     git clone https://github.com/username-anda/rekap_yuu.git
     ```

3. **Pindahkan File ke Folder Server:**
   Pindahkan folder `rekap_yuu` ke dalam direktori *document root* server Anda:
   - Untuk XAMPP: `C:\xampp\htdocs\rekap_yuu`
   - Untuk Laragon: `C:\laragon\www\rekap_yuu`

4. **Jalankan Aplikasi:**
   Buka browser (Chrome/Firefox/Edge) dan akses URL berikut:
   ```text
   http://localhost/rekap_yuu
   ```

5. **Selesai! 🎉**
   Database dan tabel akan terbuat secara otomatis. Anda sudah bisa langsung menggunakan aplikasi untuk menambah data.

---

## 📂 Struktur Direktori

```text
rekap_yuu/
│
├── assets/
│   ├── css/
│   │   └── style.css       # File CSS utama
│   └── js/
│       └── app.js          # Logika frontend (AJAX, DOM, Animasi)
│
├── partials/
│   └── modal.php           # Template pop-up form Tambah, Edit, & Konfirmasi Hapus
│
├── api.php                 # Backend REST API untuk handle CRUD (GET, POST, PUT, DELETE)
├── config.php              # Konfigurasi Database & Script Auto-Create DB/Table
├── index.php               # Halaman Utama Dashboard
├── laporan.php             # Halaman Cetak Laporan Detail
├── seed.php                # (Opsional) Script untuk memasukkan dummy data
└── README.md               # Dokumentasi Repositori ini
```

---

## 📄 Lisensi

Proyek ini bersifat *Open-Source*. Anda bebas menggunakan, memodifikasi, dan mendistribusikan sistem ini untuk keperluan pribadi, sekolah, atau institusi.
