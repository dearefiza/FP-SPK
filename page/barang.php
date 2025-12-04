<!-- judul -->
<div class="panel">
    <div class="panel-middle" id="judul">
        <img src="asset/image/barang.svg">
        <div id="judul-text">
            <h2 class="text-green">Karyawan</h2>
            Halamanan Administrator Karyawan
        </div>
    </div>
</div>
<!-- judul -->
<div class="row">
    <div class="col-4">
        <div class="panel">
            <?php
            if (@htmlspecialchars($_GET['aksi'])=='ubah'){
                include 'ubahbarang.php';
            }else{
                include 'tambahbarang.php';
            }
            ?>
        </div>
    </div>

    <div class="col-8">
        <div class="panel">
            <div class="panel-top">
                <b class="text-green">Daftar Karyawan</b>
            </div>
            <div class="panel-middle">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Divisi</th>  <!-- DITAMBAHKAN -->
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // JOIN ke tabel divisi
                        $query="
                        SELECT karyawan.*, divisi.nama_divisi 
                        FROM karyawan 
                        LEFT JOIN divisi ON karyawan.id_divisi = divisi.id_divisi
                        ";

                        $execute=$konek->query($query);

                        if ($execute->num_rows > 0){
                            $no=1;
                            while($data=$execute->fetch_array(MYSQLI_ASSOC)){
                                echo"
                                <tr id='data'>
                                    <td>$no</td>
                                    <td>$data[nama_karyawan]</td>
                                    <td>$data[nama_divisi]</td>   <!-- TAMPILKAN DIVISI -->
                                    <td>
                                        <div class='norebuttom'>
                                            <a class='btn btn-light-green' href='./?page=barang&aksi=ubah&id=".$data['id_karyawan']."'>
                                                <i class='fa fa-pencil-alt'></i>
                                            </a>
                                            <a class='btn btn-yellow' 
                                               data-a='".$data['nama_karyawan']."' 
                                               id='hapus' 
                                               href='./proses/proseshapus.php/?op=barang&id=".$data['id_karyawan']."'>
                                               <i class='fa fa-trash-alt'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td class='text-center text-green' colspan='4'>Kosong</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-bottom"></div>
        </div>
    </div>
</div>
