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
    case 'karyawan': // tambah data karyawan baru
        $query = "INSERT INTO karyawan (nama_karyawan, divisi_id)
                  VALUES ('$karyawan', '$divisi')";
        $crud->addData($query, $konek);
    break;

    case 'divisi': // tambah data divisi
        $query = "INSERT INTO divisi (nama_divisi) VALUES ('$divisi')";
        $crud->addData($query, $konek);
    break;

    case 'kriteria': // tambah data kriteria
        $cek   = "SELECT nama_kriteria FROM kriteria 
                  WHERE nama_kriteria='$nama_kriteria'";
        
        $query = "INSERT INTO kriteria (nama_kriteria, sifat_kriteria_id, bobot) 
                  VALUES ('$nama_kriteria', '$sifat_kriteria_id', '$bobot')";
        
        $crud->multiAddData($cek, $query, $konek);
    break;

    case 'penilaian': // tambah data penilaian karyawan
        // Validasi input
        if (empty($karyawan_id) || empty($divisi_id) || empty($kriteria)) {
            echo "<script>
                    alert('Data tidak lengkap!');
                    window.history.back();
                  </script>";
            exit;
        }
        
        // Cek apakah karyawan sudah pernah dinilai
        $cek = "SELECT id FROM penilaian 
                WHERE karyawan_id='$karyawan_id'";
        
        $result = $konek->query($cek);
        
        if ($result->num_rows > 0) {
            echo "<script>
                    alert('Karyawan ini sudah pernah dinilai!');
                    window.history.back();
                  </script>";
            exit;
        }
        
        // Insert ke tabel penilaian
        $queryPenilaian = "INSERT INTO penilaian (karyawan_id, divisi_id) 
                           VALUES ('$karyawan_id', '$divisi_id')";
        
        if ($konek->query($queryPenilaian)) {
            $penilaian_id = $konek->insert_id;
            
            // Insert nilai setiap kriteria
            $queryKriteria = "";
            foreach ($kriteria as $kriteria_id => $nilai) {
                $nilai = $konek->real_escape_string($nilai);
                $queryKriteria .= "INSERT INTO penilaian_kriteria (penilaian_id, kriteria_id, nilai) 
                                   VALUES ('$penilaian_id', '$kriteria_id', '$nilai');";
            }
            
            // Execute multiple queries
            if ($konek->multi_query($queryKriteria)) {
                // Clear remaining results
                while ($konek->next_result()) {;}
                
                echo "<script>
                        alert('Penilaian berhasil disimpan!');
                        window.location='../index.php?page=penilaian';
                      </script>";
            } else {
                echo "<script>
                        alert('Gagal menyimpan nilai kriteria!');
                        window.history.back();
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Gagal menyimpan penilaian!');
                    window.history.back();
                  </script>";
        }
    break;

    default:
        echo "<script>
                alert('Operasi tidak valid!');
                window.history.back();
              </script>";
    break;
}
?>