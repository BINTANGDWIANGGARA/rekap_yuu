<?php
require_once 'config.php';
$db = getDB();

$data = [
    ['Rabu', '2026-04-01', 'rafa alvaro', 'muthia', '02:28:00', 27, 'mutmainah', '14:39:00', 27],
    ['Kamis', '2026-04-02', 'ramdan faeruz kristan', 'misnu', '08:25:00', 29, 'arlin', '14:58:00', 27],
    ['Senin', '2026-04-06', 'Rakha aditya r', 'bukhari', '08:30:00', 33, 'Bukhari', '14:40:00', 33],
    ['Selasa', '2026-04-07', 'Rana Nailah Putri D', 'Miss Arlin', '08:20:00', 30, 'Pak Untung', '14:41:00', 30],
    ['Rabu', '2026-04-08', 'Razka Adya Nabil', 'muthia', '07:37:00', 26, 'Bu mut', '14:33:00', 26],
    ['Kamis', '2026-04-09', 'Restu Bumi Irawan', 'bu nurul', '07:44:00', 25, 'miss arlin', '14:30:00', 25],
    ['Senin', '2026-04-13', 'Verlita Oktavia', 'Pak Bukhari', '08:17:00', 31, 'pak ahmad bukhari', '14:43:00', 31],
    ['Rabu', '2026-04-15', 'zahra tusyifa', 'pak untung', '10:45:00', 29, 'bu mutmainah', '14:36:00', 29],
    ['Kamis', '2027-04-16', 'zaidan', 'Pak Wisnu', '08:05:00', 34, null, null, null],
    ['Jumat', '2026-04-17', 'Abdurrauf', 'Pak bukari', '08:23:00', 31, 'bu latifah nurul', '14:14:00', 31],
    ['Selasa', '2026-04-21', 'andrea', 'misn arlin', '08:00:00', 31, 'Pak Untung', '14:28:00', 31]
];

foreach ($data as $r) {
    $hari = $db->real_escape_string($r[0]);
    $tgl = $db->real_escape_string($r[1]);
    $petugas = $db->real_escape_string($r[2]);
    $guru1 = $db->real_escape_string($r[3]);
    $jam1 = $db->real_escape_string($r[4]);
    $jml1 = (int)$r[5];

    $guru2 = $r[6] !== null ? "'" . $db->real_escape_string($r[6]) . "'" : "NULL";
    $jam2 = $r[7] !== null ? "'" . $db->real_escape_string($r[7]) . "'" : "NULL";
    $jml2 = $r[8] !== null ? (int)$r[8] : "NULL";

    $sql = "INSERT INTO rekap_gawai (hari, tanggal, nama_petugas, nama_guru_awal, jam_penyerahan, jumlah_penyerahan, nama_guru_akhir, jam_pengembalian, jumlah_pengembalian)
            VALUES ('$hari', '$tgl', '$petugas', '$guru1', '$jam1', $jml1, $guru2, $jam2, $jml2)";
    $db->query($sql);
}

echo "Data berhasil dimasukkan!";
?>