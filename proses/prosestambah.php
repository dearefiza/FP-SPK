<?php
require '../connect.php';
require '../class/crud.php';
$crud = new crud($konek);

if ($_SERVER['REQUEST_METHOD']=='GET') {
    $id = @$_GET['id'];
    $op = @$_GET['op'];
} else if ($_SERVER['REQUEST_METHOD']=='POST'){
    $id = @$_POST['id'];
    $op = @$_POST['op'];
}

$karyawan   = @$_POST['karyawan'];
$divisi     = @$_POST['divisi'];
$kriteria   = @$_POST['kriteria'];
$sifat      = @$_POST['sifat'];
$nilai      = @$_POST['nilai'];
$keterangan = @$_POST['keterangan'];
$bobot      = @$_POST['bobot'];
$nama_kriteria = @$_POST['nama_kriteria'];
$id_sifat      = @$_POST['id_sifat'];



switch ($op){
    case 'karyawan': // tambah data karyawan
        $query = "INSERT INTO karyawan (nama_karyawan, id_divisi)
                  VALUES ('$karyawan', '$divisi')";
        $crud->addData($query,$konek);
    break;

    case 'divisi':
        $query = "INSERT INTO divisi (nama_divisi) VALUES ('$divisi')";
        $crud->addData($query,$konek);
    break;

case 'kriteria': // tambah data kriteria

    $cek   = "SELECT nama_kriteria FROM kriteria 
              WHERE nama_kriteria='$nama_kriteria'";

    $query = "INSERT INTO kriteria (nama_kriteria, id_sifat, bobot) 
              VALUES ('$nama_kriteria', '$id_sifat', '$bobot')";

    $crud->multiAddData($cek, $query, $konek);

break;



    case 'bobot': // tambah data bobot
        $cek   = "SELECT id_bobotkriteria FROM bobot_kriteria WHERE id_jenisbarang='$barang'";
        $query = "";
        for ($i=0;$i<count($kriteria);$i++){
            $query .= "INSERT INTO bobot_kriteria (id_jenisbarang,id_kriteria,bobot) 
                       VALUES ('$barang','$kriteria[$i]','$bobot[$i]');";
        }
        $crud->multiAddData($cek,$query,$konek);
    break;

    case 'nilai': //tambah data nilai
        $cek   = "SELECT id_supplier FROM nilai_supplier WHERE id_supplier='$supplier'";
        $query = "";
        for ($i=0;$i<count($nilai);$i++){
            $query .= "INSERT INTO nilai_supplier (id_supplier,id_jenisbarang,id_kriteria,id_nilaikriteria) 
                       VALUES ('$supplier','$barang','$kriteria[$i]','$nilai[$i]');";
        }
        $crud->multiAddData($cek,$query,$konek);
    break;
}
