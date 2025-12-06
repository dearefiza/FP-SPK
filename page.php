<?php
$page = htmlspecialchars($_GET['page'] ?? '');

switch ($page) {
    case '':
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

    case 'penilaian':
        include 'page/nilai.php';
        break;

    case 'hasil':
        include 'page/hasil.php';
        break;

    case 'tambahbobot':
        include 'page/tambahbobot.php';
        break;

    default:
        include 'page/404.php';
}