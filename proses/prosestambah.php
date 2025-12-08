<?php
// Biar semua error kelihatan
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../connect.php';
require '../class/crud.php';
$crud = new crud($konek);

// ambil op
$op = $_REQUEST['op'] ?? '';

/**
 * Helper untuk munculin error SQL biar kelihatan jelas
 */
function sql_error_and_back($konek, $msg = 'Error SQL') {
    $err = addslashes($konek->error);
    echo "<script>alert('$msg: $err');history.back();</script>";
    exit;
}

switch ($op) {

    /* =============================
       TAMBAH KARYAWAN
    ==============================*/
    case 'karyawan':
        $nama_karyawan = $_POST['karyawan'] ?? '';
        // dari form tambahkaryawan: name="divisi" ISINYA id_divisi
        $divisi_id     = $_POST['divisi'] ?? '';

        if ($nama_karyawan === '' || $divisi_id === '') {
            echo "<script>alert('Nama karyawan / divisi belum diisi');history.back();</script>";
            exit;
        }

        $query = "INSERT INTO karyawan (nama_karyawan, divisi_id)
                  VALUES ('$nama_karyawan', '$divisi_id')";
        if (!$konek->query($query)) {
            sql_error_and_back($konek, 'Gagal menyimpan karyawan');
        }

        echo "<script>alert('Karyawan berhasil disimpan');window.location='../index.php?page=karyawan';</script>";
    break;

    /* =============================
       TAMBAH DIVISI
    ==============================*/
    case 'divisi':
        $divisi = $_POST['divisi'] ?? '';

        if ($divisi === '') {
            echo "<script>alert('Nama divisi belum diisi');history.back();</script>";
            exit;
        }

        $query = "INSERT INTO divisi (nama_divisi) VALUES ('$divisi')";
        if (!$konek->query($query)) {
            sql_error_and_back($konek, 'Gagal menyimpan divisi');
        }

        echo "<script>alert('Divisi berhasil disimpan');window.location='../index.php?page=divisi';</script>";
    break;

    /* =============================
       TAMBAH KRITERIA
    ==============================*/
    case 'kriteria':
        $nama_kriteria     = $_POST['nama_kriteria'] ?? '';
        $sifat_kriteria_id = $_POST['sifat_kriteria_id'] ?? '';
        $bobot             = $_POST['bobot'] ?? '';

        if ($nama_kriteria === '' || $sifat_kriteria_id === '' || $bobot === '') {
            echo "<script>alert('Data kriteria belum lengkap');history.back();</script>";
            exit;
        }

        $cek   = "SELECT nama_kriteria FROM kriteria WHERE nama_kriteria = '$nama_kriteria'";
        $query = "INSERT INTO kriteria (nama_kriteria, sifat_kriteria_id, bobot)
                  VALUES ('$nama_kriteria', '$sifat_kriteria_id', '$bobot')";
        $crud->multiAddData($cek, $query, $konek);
    break;

    /* =============================
       TAMBAH PENILAIAN KARYAWAN
    ==============================*/
    case 'penilaian':
        // dari tambahnilai.php (hidden input)
        $karyawan_id = $_POST['karyawan_id'] ?? '';
        $divisi_id   = $_POST['divisi_id'] ?? '';
        // array: kriteria[id_kriteria] = nilai
        $kriteria    = $_POST['kriteria'] ?? [];

        // cek isi
        if ($karyawan_id === '' || $divisi_id === '' || empty($kriteria)) {
            echo "<script>alert('Data tidak lengkap! Pastikan ID karyawan terisi dan semua nilai diisi.');history.back();</script>";
            exit;
        }

        // CEK: karyawan sudah pernah dinilai?
        $cek = "SELECT id_penilaian FROM penilaian WHERE karyawan_id = '$karyawan_id'";
        $res = $konek->query($cek);
        if (!$res) {
            sql_error_and_back($konek, 'Gagal mengecek penilaian');
        }

        if ($res->num_rows > 0) {
            echo "<script>alert('Karyawan ini sudah pernah dinilai!');history.back();</script>";
            exit;
        }

        // INSERT ke penilaian
        $q1 = "INSERT INTO penilaian (karyawan_id, divisi_id)
               VALUES ('$karyawan_id', '$divisi_id')";
        if (!$konek->query($q1)) {
            sql_error_and_back($konek, 'Gagal menyimpan penilaian');
        }

        $penilaian_id = $konek->insert_id;

        // SUSUN multi-query untuk penilaian_kriteria
        $multi = '';
        foreach ($kriteria as $id_kriteria => $nilai) {
            if ($nilai === '' || $nilai === null) {
                continue;
            }
            $nilai = $konek->real_escape_string($nilai);
            $id_kriteria = (int) $id_kriteria;

            $multi .= "
                INSERT INTO penilaian_kriteria (penilaian_id, kriteria_id, nilai)
                VALUES ('$penilaian_id', '$id_kriteria', '$nilai');
            ";
        }

        if ($multi === '') {
            echo "<script>alert('Tidak ada nilai kriteria yang dikirim.');history.back();</script>";
            exit;
        }

        if (!$konek->multi_query($multi)) {
            sql_error_and_back($konek, 'Gagal menyimpan nilai kriteria');
        }

        // bersihkan result buffer multi_query
        while ($konek->more_results() && $konek->next_result()) {;}

        echo "<script>
                alert('Penilaian berhasil disimpan!');
                window.location='../index.php?page=penilaian';
              </script>";
    break;

    /* =============================
       DEFAULT
    ==============================*/
    default:
        echo "<script>alert('Operasi tidak valid!');history.back();</script>";
    break;
}
?>
