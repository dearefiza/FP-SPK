<?php
// Aktifkan error reporting untuk debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sesuaikan dengan file koneksi Anda
include "connect.php"; // atau sesuaikan path

// Set header JSON
header('Content-Type: application/json');

if (isset($_GET['term'])) {
    $term = $konek->real_escape_string($_GET['term']);
    
    // Query sesuai struktur database BARU
    $query = "SELECT k.id_karyawan, k.nama_karyawan, k.divisi_id, d.nama_divisi 
              FROM karyawan k 
              LEFT JOIN divisi d ON k.divisi_id = d.id_divisi 
              WHERE k.nama_karyawan LIKE '%$term%' 
              ORDER BY k.nama_karyawan 
              LIMIT 10";
    
    $result = $konek->query($query);
    
    if (!$result) {
        echo json_encode(['error' => $konek->error]);
        exit;
    }
    
    $data = array();
    
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'id' => $row['id_karyawan'],
            'nama' => $row['nama_karyawan'],
            'divisi_id' => $row['divisi_id'],
            'nama_divisi' => $row['nama_divisi']
        );
    }
    
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Parameter term tidak ditemukan']);
}
?>