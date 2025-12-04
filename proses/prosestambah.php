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
$divisi     = @$_POST['divisi'];   // <-- INI PENTING
$kriteria   = @$_POST['kriteria'];
$sifat      = @$_POST['sifat'];
$nilai      = @$_POST['nilai'];
$keterangan = @$_POST['keterangan'];
$bobot      = @$_POST['bobot'];


switch ($op){
    case 'karyawan': // sekarang: tambah data karyawan
        $query = "INSERT INTO karyawan (nama_karyawan, id_divisi)
                  VALUES ('$karyawan', '$divisi')";
        $crud->addData($query,$konek);
    break;

    case 'divisi':
        $query = "INSERT INTO divisi (nama_divisi) VALUES ('$divisi')";
        $crud->addData($query,$konek);
    break;

    case 'kriteria': //tambah data kriteria
        $cek   = "SELECT namaKriteria FROM kriteria WHERE namaKriteria='$kriteria'";
        $query = "INSERT INTO kriteria (namaKriteria,sifat) VALUES ('$kriteria','$sifat')";
        $crud->multiAddData($cek,$query,$konek);
    break;

    case 'subkriteria': //tambah data sub kriteria
        $cek   = "SELECT id_nilaikriteria FROM nilai_kriteria 
                  WHERE (id_kriteria='$kriteria' AND nilai ='$nilai') 
                     OR (id_kriteria='$kriteria' AND keterangan = '$keterangan')";
        $query = "INSERT INTO nilai_kriteria (id_kriteria,nilai,keterangan) 
                  VALUES ('$kriteria','$nilai','$keterangan');";
        $crud->multiAddData($cek,$query,$konek);
    break;

    case 'bobot': //tambah data bobot
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
