<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../connect.php";

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $konek->real_escape_string($_GET['id']);

    $query = "SELECT k.id_karyawan, k.nama_karyawan, k.divisi_id, d.nama_divisi 
              FROM karyawan k 
              LEFT JOIN divisi d ON k.divisi_id = d.id_divisi 
              WHERE k.id_karyawan = '$id'";

    $result = $konek->query($query);

    if (!$result) {
        echo json_encode(['success' => false, 'error' => $konek->error]);
        exit;
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'data' => [
                'id_karyawan' => $row['id_karyawan'],
                'nama_karyawan' => $row['nama_karyawan'],
                'divisi_id' => $row['divisi_id'],
                'nama_divisi' => $row['nama_divisi']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'ID Karyawan tidak ditemukan'
        ]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Parameter id tidak ditemukan']);
}
?>