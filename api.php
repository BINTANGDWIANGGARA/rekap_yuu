<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

$db = getDB();

switch ($method) {
    case 'GET':
        handleGet($db);
        break;
    case 'POST':
        handlePost($db);
        break;
    case 'PUT':
        handlePut($db);
        break;
    case 'DELETE':
        handleDelete($db);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
}

$db->close();

// ─── READ ──────────────────────────────────────────────────────────────────
function handleGet($db) {
    $search = $_GET['search'] ?? '';
    $date_from = $_GET['date_from'] ?? '';
    $date_to   = $_GET['date_to']   ?? '';

    $where = [];
    $params = [];
    $types  = '';

    if ($search !== '') {
        $like = "%$search%";
        $where[] = "(nama_petugas LIKE ? OR nama_guru_awal LIKE ? OR nama_guru_akhir LIKE ?)";
        $params = array_merge($params, [$like, $like, $like]);
        $types .= 'sss';
    }
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
    $sql .= " ORDER BY tanggal DESC, jam_penyerahan DESC";

    $stmt = $db->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $data]);
}

// ─── CREATE ────────────────────────────────────────────────────────────────
function handlePost($db) {
    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) {
        echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
        return;
    }

    $required = ['hari','tanggal','nama_petugas','nama_guru_awal','jam_penyerahan',
                 'jumlah_penyerahan','nama_guru_akhir','jam_pengembalian','jumlah_pengembalian'];
    foreach ($required as $field) {
        if (!isset($body[$field]) || $body[$field] === '') {
            echo json_encode(['success' => false, 'message' => "Field '$field' wajib diisi"]);
            return;
        }
    }

    $sql = "INSERT INTO rekap_gawai
            (hari, tanggal, nama_petugas, nama_guru_awal, jam_penyerahan,
             jumlah_penyerahan, nama_guru_akhir, jam_pengembalian, jumlah_pengembalian)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    $stmt->bind_param('sssssisis',
        $body['hari'], $body['tanggal'], $body['nama_petugas'],
        $body['nama_guru_awal'], $body['jam_penyerahan'],
        $body['jumlah_penyerahan'], $body['nama_guru_akhir'],
        $body['jam_pengembalian'], $body['jumlah_pengembalian']
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Data berhasil disimpan', 'id' => $db->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan: ' . $stmt->error]);
    }
}

// ─── UPDATE ────────────────────────────────────────────────────────────────
function handlePut($db) {
    $body = json_decode(file_get_contents('php://input'), true);
    $id   = intval($_GET['id'] ?? 0);

    if (!$id || !$body) {
        echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
        return;
    }

    $sql = "UPDATE rekap_gawai SET
            hari=?, tanggal=?, nama_petugas=?, nama_guru_awal=?,
            jam_penyerahan=?, jumlah_penyerahan=?,
            nama_guru_akhir=?, jam_pengembalian=?, jumlah_pengembalian=?
            WHERE id=?";

    $stmt = $db->prepare($sql);
    $stmt->bind_param('sssssisisi',
        $body['hari'], $body['tanggal'], $body['nama_petugas'],
        $body['nama_guru_awal'], $body['jam_penyerahan'],
        $body['jumlah_penyerahan'], $body['nama_guru_akhir'],
        $body['jam_pengembalian'], $body['jumlah_pengembalian'],
        $id
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Data berhasil diperbarui']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui: ' . $stmt->error]);
    }
}

// ─── DELETE ────────────────────────────────────────────────────────────────
function handleDelete($db) {
    $id = intval($_GET['id'] ?? 0);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
        return;
    }

    $stmt = $db->prepare("DELETE FROM rekap_gawai WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus: ' . $stmt->error]);
    }
}
?>
