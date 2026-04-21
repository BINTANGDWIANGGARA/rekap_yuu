<?php
require_once 'config.php';
$db = getDB();

$date_from = $_GET['date_from'] ?? '';
$date_to   = $_GET['date_to']   ?? '';

$where = [];
$params = [];
$types = '';

if ($date_from !== '') {
    $where[] = "tanggal >= ?";
    $params[] = $date_from;
    $types .= 's';
}
if ($date_to !== '') {
    $where[] = "tanggal <= ?";
    $params[] = $date_to;
    $types .= 's';
}

$sql = "SELECT * FROM rekap_gawai";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY tanggal ASC, jam_penyerahan ASC";

$stmt = $db->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$data = [];
$total_serah = 0;
$total_kembali = 0;

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
    $total_serah += (int)$row['jumlah_penyerahan'];
    $total_kembali += (int)$row['jumlah_pengembalian'];
}
$total_selisih = $total_serah - $total_kembali;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekap Penyerahan & Pengembalian Gawai</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0; 
            padding: 40px; 
            color: #000;
        }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0 0 10px 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 0; font-size: 14px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 13px; }
        th, td { border: 1px solid #000; padding: 8px 10px; text-align: center; }
        th { font-weight: 600; }
        .text-left { text-align: left; }
        
        /* Custom Colors based on Excel */
        .bg-dark { background-color: #000; color: #fff; }
        .bg-green { background-color: #dcfce7; }
        .bg-yellow { background-color: #fef08a; }
        
        .summary { width: 350px; float: right; border: 2px solid #000; }
        .summary th, .summary td { padding: 8px 12px; font-size: 14px; border: 1px solid #000; }
        .summary th { background: #f8f9fa; }
        
        .signature { clear: both; float: right; margin-top: 50px; text-align: center; width: 250px; }
        .signature p { margin: 5px 0; }
        .signature .name { margin-top: 80px; font-weight: 600; text-decoration: underline; }
        
        .print-btn { 
            position: fixed; top: 20px; left: 20px; 
            background: #0f172a; color: white; border: none; 
            padding: 10px 20px; border-radius: 6px; cursor: pointer; 
            font-family: inherit; font-weight: 600; font-size: 14px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            display: flex; align-items: center; gap: 8px;
        }
        .print-btn:hover { background: #1e293b; }
        
        @media print {
            body { padding: 0; }
            .print-btn { display: none; }
            @page { size: landscape; margin: 15mm; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Cetak Laporan
    </button>

    <div class="header">
        <h1>Laporan Rekap Penyerahan dan Pengembalian Gawai</h1>
        <?php if ($date_from && $date_to): ?>
            <p>Periode: <?= date('d/m/Y', strtotime($date_from)) ?> - <?= date('d/m/Y', strtotime($date_to)) ?></p>
        <?php elseif ($date_from): ?>
            <p>Periode: Sejak <?= date('d/m/Y', strtotime($date_from)) ?></p>
        <?php elseif ($date_to): ?>
            <p>Periode: Sampai <?= date('d/m/Y', strtotime($date_to)) ?></p>
        <?php else: ?>
            <p>Periode: Semua Waktu</p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" class="bg-dark">Hari / Tanggal</th>
                <th rowspan="2" class="bg-dark">Nama Petugas Kelas</th>
                <th colspan="3" class="bg-green" style="color:#000;">Penyerahan Gawai</th>
                <th colspan="3" class="bg-yellow" style="color:#000;">Pengembalian Gawai</th>
            </tr>
            <tr>
                <th class="bg-green" style="color:#059669;">Nama Guru (jam pertama)</th>
                <th class="bg-green" style="color:#059669;">Jam Penyerahan</th>
                <th class="bg-green" style="color:#059669;">Jumlah Gawai Penyerahan</th>
                <th class="bg-yellow" style="color:#ca8a04;">Nama Guru (jam terakhir)</th>
                <th class="bg-yellow" style="color:#ca8a04;">Jam Pengembalian</th>
                <th class="bg-yellow" style="color:#ca8a04;">Jumlah Gawai Pengembalian</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
            <tr>
                <td colspan="8" style="padding: 20px;">Tidak ada data pada periode ini.</td>
            </tr>
            <?php else: ?>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td class="text-left"><?= htmlspecialchars($row['hari']) ?>, <?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                    <td class="text-left"><?= htmlspecialchars($row['nama_petugas']) ?></td>
                    
                    <!-- Penyerahan -->
                    <td class="text-left"><?= htmlspecialchars($row['nama_guru_awal']) ?></td>
                    <td><?= substr($row['jam_penyerahan'], 0, 5) ?></td>
                    <td><?= $row['jumlah_penyerahan'] ?></td>
                    
                    <!-- Pengembalian -->
                    <td class="text-left"><?= $row['nama_guru_akhir'] ? htmlspecialchars($row['nama_guru_akhir']) : '' ?></td>
                    <td><?= $row['jam_pengembalian'] ? substr($row['jam_pengembalian'], 0, 5) : '' ?></td>
                    <td><?= $row['jumlah_pengembalian'] !== null ? $row['jumlah_pengembalian'] : '' ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <th class="text-left">Total Gawai Diserahkan</th>
            <td><strong><?= $total_serah ?></strong></td>
        </tr>
        <tr>
            <th class="text-left">Total Gawai Dikembalikan</th>
            <td><strong><?= $total_kembali ?></strong></td>
        </tr>
        <tr>
            <th class="text-left">Selisih (Belum Kembali)</th>
            <td><strong><?= $total_selisih ?></strong></td>
        </tr>
    </table>

    <div class="signature">
        <p>Mengetahui,</p>
        <p>Koordinator / Admin</p>
        <p class="name">( ......................................... )</p>
    </div>
</body>
</html>