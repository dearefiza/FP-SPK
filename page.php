<?php
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'beranda';

switch ($page) {

    case null:
    case 'beranda':
        include 'page/beranda.php';
        break;

    case 'karyawan':
        include 'page/karyawan.php';
        break;

    case 'divisi':
        include 'page/divisi.php';
        break;

    case 'kriteria':
        include 'page/kriteria.php';
        break;

    case 'subkriteria':
        include 'page/subkriteria.php';
        break;

    case 'bobot':
        include 'page/bobot.php';
        break;

    case 'penilaian':
    case 'penilaiankaryawan': // opsional, biar tidak error lagi
        include 'page/nilai.php';
        break;

    case 'hasil':
        include 'page/hasil.php';
        break;

    case 'tambahbobot':
        include 'page/tambahbobot.php';
        break;

    default:
        echo "<h3 style='padding:30px;'>Halaman tidak ditemukan.</h3>";
}
?>
