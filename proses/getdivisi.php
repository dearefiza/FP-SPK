<?php
include __DIR__ . '/../connect.php';

if (isset($_GET['nama_karyawan'])) {
    $nama_karyawan = $_GET['nama_karyawan'];

    // JOIN tabel divisi
    $query = "
        SELECT d.nama_divisi 
        FROM karyawan k
        JOIN divisi d ON k.divisi_id = d.id_divisi
        WHERE k.nama_karyawan = ?
    ";

    $stmt = $konek->prepare($query);
    $stmt->bind_param("s", $nama_karyawan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['nama_divisi'];
    } else {
        echo "Divisi tidak ditemukan.";
    }

    $stmt->close();
} else {
    echo "Nama karyawan tidak dikirim.";
}

$konek->close();
?>
