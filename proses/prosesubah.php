<?php
require '../connect.php';

// ambil op dan id
$op = $_POST['op'] ?? $_GET['op'] ?? '';
$id = $_POST['id'] ?? $_GET['id'] ?? '';

/*******************************
 * UPDATE DATA KARYAWAN
 *******************************/
if ($op == 'karyawan') {

    $nama   = $_POST['karyawan'] ?? '';
    $divisi = $_POST['divisi'] ?? '';

    if ($nama == '' || $divisi == '') {
        echo "<script>alert('Data karyawan belum lengkap');history.back();</script>";
        exit;
    }

    $query = "UPDATE karyawan 
              SET nama_karyawan='$nama', divisi_id='$divisi'
              WHERE id_karyawan='$id'";

    if ($konek->query($query)) {
        echo "<script>alert('Karyawan berhasil diubah'); window.location='../index.php?page=karyawan';</script>";
    } else {
        echo "<script>alert('Gagal mengubah: ".$konek->error."');history.back();</script>";
    }
    exit;
}


/*******************************
 * UPDATE DATA DIVISI
 *******************************/
if ($op == 'divisi') {

    $nama = $_POST['divisi'] ?? '';

    if ($nama == '') {
        echo "<script>alert('Nama divisi tidak boleh kosong');history.back();</script>";
        exit;
    }

    $query = "UPDATE divisi SET nama_divisi='$nama' WHERE id_divisi='$id'";

    if ($konek->query($query)) {
        echo "<script>alert('Divisi berhasil diubah'); window.location='../index.php?page=divisi';</script>";
    } else {
        echo "<script>alert('Gagal mengubah: ".$konek->error."');history.back();</script>";
    }
    exit;
}


/*******************************
 * UPDATE DATA KRITERIA
 *******************************/
if ($op == 'kriteria') {

    $nama   = $_POST['nama_kriteria'] ?? '';
    $sifat  = $_POST['sifat_kriteria_id'] ?? '';
    $bobot  = $_POST['bobot'] ?? '';

    if ($nama == '' || $sifat == '' || $bobot == '') {
        echo "<script>alert('Data kriteria belum lengkap');history.back();</script>";
        exit;
    }

    // Cek duplikasi nama
    $cek = $konek->query("SELECT id_kriteria FROM kriteria 
                          WHERE nama_kriteria='$nama' 
                          AND id_kriteria != '$id'");
    if ($cek->num_rows > 0) {
        echo "<script>alert('Nama kriteria sudah ada!');history.back();</script>";
        exit;
    }

    $query = "UPDATE kriteria SET 
                nama_kriteria='$nama',
                sifat_kriteria_id='$sifat',
                bobot='$bobot'
              WHERE id_kriteria='$id'";

    if ($konek->query($query)) {
        echo "<script>alert('Kriteria berhasil diubah'); window.location='../index.php?page=kriteria';</script>";
    } else {
        echo "<script>alert('Gagal mengubah: ".$konek->error."');history.back();</script>";
    }
    exit;
}


/*******************************
 * UPDATE PENILAIAN
 *******************************/
if ($op == 'penilaian') {

    $id_penilaian = $_POST['id_penilaian'] ?? '';
    $kriteria     = $_POST['kriteria'] ?? [];

    if (!$id_penilaian || empty($kriteria)) {
        echo "<script>alert('Data penilaian tidak lengkap');history.back();</script>";
        exit;
    }

    // Hapus nilai lama
    $konek->query("DELETE FROM penilaian_kriteria WHERE penilaian_id='$id_penilaian'");

    // Insert nilai baru
    $multi = "";
    foreach ($kriteria as $idk => $nilai) {
        $nilai = floatval($nilai);
        $idk   = intval($idk);

        $multi .= "INSERT INTO penilaian_kriteria 
                    (penilaian_id, kriteria_id, nilai)
                    VALUES ('$id_penilaian', '$idk', '$nilai');";
    }

    if ($konek->multi_query($multi)) {
        while ($konek->more_results() && $konek->next_result()) {}
        echo "<script>alert('Penilaian berhasil diubah'); window.location='../index.php?page=penilaian';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan perubahan: ".$konek->error."');history.back();</script>";
    }

    exit;
}


// Jika op tidak dikenal
echo "<script>alert('Operasi tidak valid');history.back();</script>";
exit;
