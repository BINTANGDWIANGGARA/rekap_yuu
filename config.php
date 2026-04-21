<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rekap_yuu');

function getDB() {
    // Connect without DB first to create it if it doesn't exist
    $temp_conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    if ($temp_conn->connect_error) {
        http_response_code(500);
        die(json_encode(['success' => false, 'message' => 'Koneksi server database gagal: ' . $temp_conn->connect_error]));
    }
    
    // Create DB if not exists
    $temp_conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $temp_conn->close();

    // Connect to the actual DB
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(['success' => false, 'message' => 'Koneksi database gagal: ' . $conn->connect_error]));
    }
    $conn->set_charset('utf8mb4');
    
    // Create table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS rekap_gawai (
        id INT AUTO_INCREMENT PRIMARY KEY,
        hari VARCHAR(20) NOT NULL,
        tanggal DATE NOT NULL,
        nama_petugas VARCHAR(100) NOT NULL,
        nama_guru_awal VARCHAR(100) NOT NULL,
        jam_penyerahan TIME NOT NULL,
        jumlah_penyerahan INT NOT NULL,
        nama_guru_akhir VARCHAR(100),
        jam_pengembalian TIME,
        jumlah_pengembalian INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
    
    return $conn;
}
?>
