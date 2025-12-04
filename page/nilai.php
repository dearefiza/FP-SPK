<div class="panel">
    <div class="panel-middle" id="judul">
        <img src="asset/image/bobot.png" class="icon">
        <div id="judul-text">
            <h2 class="text-green">Penilaian</h2>
            Halamanan Administrator Penilaian
        </div>
    </div>
</div>
<!-- judul -->
<div class="row">
    <div class="col-4">
        <div class="panel">
            <?php
            if (@htmlspecialchars($_GET['aksi'])=='ubah'){
                include 'ubahnilai.php';
            }else{
                include 'tambahnilai.php';
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
                                <th>Nama Karyawan</th>
                                <th>Divisi</th>
                                <th>K1</th>
                                <th>K2</th>
                                <th>K3</th>
                                <th>K4</th>
                                <th>K5</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query = "
                            SELECT 
                                p.id AS penilaian_id,
                                karyawan.nama_karyawan,
                                divisi.nama_divisi,
                                MAX(CASE WHEN pk.kriteria_id = 1 THEN pk.nilai END) AS K1,
                                MAX(CASE WHEN pk.kriteria_id = 2 THEN pk.nilai END) AS K2,
                                MAX(CASE WHEN pk.kriteria_id = 3 THEN pk.nilai END) AS K3,
                                MAX(CASE WHEN pk.kriteria_id = 4 THEN pk.nilai END) AS K4,
                                MAX(CASE WHEN pk.kriteria_id = 5 THEN pk.nilai END) AS K5
                            FROM penilaian p
                            JOIN karyawan ON p.karyawan_id = karyawan.id
                            JOIN divisi ON p.divisi_id = divisi.id
                            LEFT JOIN penilaian_kriteria pk ON p.id = pk.penilaian_id
                            GROUP BY p.id, karyawan.nama_karyawan, divisi.nama_divisi
                            ORDER BY p.id ASC
                        ";

                        $execute = $konek->query($query);
                        if ($execute->num_rows > 0){
                            $no = 1;
                            while($data = $execute->fetch_array(MYSQLI_ASSOC)){
                                echo "
                                <tr id='data'>
                                    <td>$no</td>
                                    <td>{$data['nama_karyawan']}</td>
                                    <td>{$data['nama_divisi']}</td>
                                    <td>{$data['K1']}</td>
                                    <td>{$data['K2']}</td>
                                    <td>{$data['K3']}</td>
                                    <td>{$data['K4']}</td>
                                    <td>{$data['K5']}</td>
                                    <td>
                                        <div class='norebuttom'>
                                            <a class='btn btn-light-green' href='./?page=penilaian&aksi=ubah&id={$data['penilaian_id']}'><i class='fa fa-pencil-alt'></i></a>
                                            <a class='btn btn-yellow' data-a='{$data['nama_karyawan']}' id='hapus' href='./proses/proseshapus.php/?op=penilaian&id={$data['penilaian_id']}'><i class='fa fa-trash-alt'></i></a>
                                        </div>
                                    </td>
                                </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td class='text-center text-green' colspan='9'>Kosong</td></tr>";
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