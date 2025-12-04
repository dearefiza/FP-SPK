<?php
include '../koneksi.php';

$id_karyawan = intval($_GET['id_karyawan']);
$result = $konek->query("SELECT d.namaDivisi 
                         FROM karyawan k 
                         LEFT JOIN divisi d ON k.id_divisi = d.id_divisi 
                         WHERE k.id_karyawan = $id_karyawan");
$row = $result->fetch_assoc();
echo json_encode($row);
?>
