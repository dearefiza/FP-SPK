<?php
include __DIR__ . '/../connect.php';

$keyword = $_GET['keyword'] ?? '';

$sql = mysqli_query($konek,
    "SELECT nama_karyawan FROM karyawan 
     WHERE nama_karyawan LIKE '%$keyword%' LIMIT 10");

while ($row = mysqli_fetch_assoc($sql)) {
    echo "<option value='".$row['nama_karyawan']."'>";
}
?>
