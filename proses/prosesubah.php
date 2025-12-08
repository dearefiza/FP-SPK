<?php
require '../connect.php';
require '../class/crud.php';
$crud=new crud($konek);
if ($_SERVER['REQUEST_METHOD']=='GET') {
    $id=@$_GET['id'];
    $op=@$_GET['op'];
}else if ($_SERVER['REQUEST_METHOD']=='POST'){
    $id=@$_POST['id'];
    $op=@$_POST['op'];
}

$karyawan_id = @$_POST['karyawan_id'];
$karyawan    = @$_POST['karyawan'];
$divisi      = @$_POST['divisi'];
$divisi_id   = @$_POST['divisi_id'];
$kriteria    = @$_POST['kriteria'];
$sifat       = @$_POST['sifat'];
$bobot       = @$_POST['bobot'];
$nama_kriteria = @$_POST['nama_kriteria'];
$sifat_kriteria_id = @$_POST['sifat_kriteria_id'];

switch ($op){
    case 'karyawan':
        $query="UPDATE karyawan SET nama_karyawan='$karyawan', divisi_id='$divisi' WHERE id_karyawan='$id'";
        $crud->update($query,$konek,'./?page=karyawan');
        break;
    case 'divisi':
        $query="UPDATE divisi SET nama_divisi='$divisi' WHERE id_divisi='$id'";
        $crud->update($query,$konek,'./?page=divisi');
        break;
    case 'kriteria':
         $cek = "SELECT nama_kriteria FROM kriteria 
            WHERE nama_kriteria='$kriteria' AND id_kriteria!='$id'";
            // Query update
            $query = "UPDATE kriteria 
                    SET nama_kriteria='$kriteria', 
                        sifat_kriteria_id='$sifat',
                        bobot='$bobot'
                    WHERE id_kriteria='$id'";
            $crud->multiUpdate($cek, $query, $konek, './?page=kriteria');
        break;
    case 'penilaian':
            $id_penilaian = $_POST['id_penilaian'];
            $kriteria = $_POST['kriteria'];

            if (!$kriteria) {
                echo "<script>alert('Data nilai tidak lengkap!'); window.history.back();</script>";
                exit;
            }

            // HAPUS NILAI LAMA
            $konek->query("DELETE FROM penilaian_kriteria WHERE penilaian_id = '$id_penilaian'");

            // INSERT NILAI BARU
            $multi = "";
            foreach ($kriteria as $idk => $nilai) {
                $idk = intval($idk);
                $nilai = floatval($nilai);

                $multi .= "INSERT INTO penilaian_kriteria (penilaian_id, kriteria_id, nilai) 
                        VALUES ($id_penilaian, $idk, $nilai);";
            }

            if ($konek->multi_query($multi)) {
                while ($konek->more_results() && $konek->next_result()) {}

                echo "<script>
                        alert('Perubahan berhasil disimpan!');
                        window.location='../index.php?page=penilaian';
                    </script>";
            } else {
                echo "<script>alert('Gagal menyimpan perubahan!'); window.history.back();</script>";
            }
        break;

}