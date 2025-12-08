<?php
require '../connect.php';
require '../class/crud.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = @$_GET['id'];
    $op = @$_GET['op'];
} else {
    $id = @$_POST['id'];
    $op = @$_POST['op'];
}

$crud = new crud();

switch ($op) {

    case 'karyawan':
        $query = "DELETE FROM karyawan WHERE id_karyawan='$id'";
        $crud->delete($query, $konek);
        break;

    case 'divisi':
        $query = "DELETE FROM divisi WHERE id_divisi='$id'";
        $crud->delete($query, $konek);
        break;

    case 'kriteria':
        $query = "DELETE FROM kriteria WHERE id_kriteria='$id'";
        $crud->delete($query, $konek);
        break;

    case 'subkriteria':
        $query = "DELETE FROM nilai_kriteria WHERE id_nilaikriteria='$id'";
        $crud->delete($query, $konek);
        break;

    case 'bobot':
        $query = "DELETE FROM bobot_kriteria WHERE id_karyawan='$id'";
        $crud->delete($query, $konek);
        break;

    case 'nilai':
        $query = "DELETE FROM nilai_supplier WHERE id_supplier='$id'";
        $crud->delete($query, $konek);
        break;

    /* --------------- FIX UTAMA ---------------- */
    case 'penilaian':
        // hapus nilai kriteria terlebih dahulu
        $q1 = "DELETE FROM penilaian_kriteria WHERE penilaian_id='$id'";
        $konek->query($q1);

        // hapus data utama penilaian
        $q2 = "DELETE FROM penilaian WHERE id_penilaian='$id'";
        $crud->delete($q2, $konek);
        break;
}
