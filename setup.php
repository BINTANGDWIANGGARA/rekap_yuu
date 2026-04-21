<?php
$conn = new mysqli('localhost', 'root', '', '');
if ($conn->connect_error) {
    die("❌ Koneksi gagal: " . $conn->connect_error);
}

// Buat database
$conn->query("CREATE DATABASE IF NOT EXISTS `rekap_yuu` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db('rekap_yuu');

// Buat tabel
$sql = "CREATE TABLE IF NOT EXISTS `rekap_gawai` (
    `id`                INT AUTO_INCREMENT PRIMARY KEY,
    `hari`              VARCHAR(20) NOT NULL,
    `tanggal`           DATE NOT NULL,
    `nama_petugas`      VARCHAR(100) NOT NULL,
    `nama_guru_awal`    VARCHAR(100) NOT NULL,
    `jam_penyerahan`    TIME NOT NULL,
    `jumlah_penyerahan` INT NOT NULL DEFAULT 0,
    `nama_guru_akhir`   VARCHAR(100) NOT NULL,
    `jam_pengembalian`  TIME NOT NULL,
    `jumlah_pengembalian` INT NOT NULL DEFAULT 0,
    `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql)) {
    echo "✅ Database & Tabel berhasil dibuat!<br>";
    echo "➡️ <a href='index.php'>Klik di sini untuk menuju Dashboard</a>";
} else {
    echo "❌ Gagal membuat tabel: " . $conn->error;
}
$conn->close();
?>
