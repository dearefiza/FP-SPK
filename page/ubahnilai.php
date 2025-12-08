<?php
$a=htmlspecialchars(@$_GET['a']);
$b=htmlspecialchars(@$_GET['b']);
$getData=array();
$querylihat="SELECT id_penilaian_kriteria FROM penilaian_kriteria WHERE kriteria_id='$a' AND kriteria_id='$b'";
$getnilaiKriteria=$konek->query($querylihat);
while ($data=$getnilaiKriteria->fetch_array(MYSQLI_ASSOC)) {
    array_push($getData,$data['id_penilaian_kriteria']);
}
?>
<div class="panel-top panel-top-edit">
    <b><i class="fa fa-pencil-alt"></i> Ubah data</b>
</div>
<form id="form" action="./proses/prosesubah.php" method="POST">
    <input type="hidden" value="nilai" name="op">
    <div class="panel-middle">
        <div class="group-input">
            <?php
            $query="SELECT nama_divisi FROM divisi WHERE id_divisi='$a'";
            $execute=$konek->query($query);
            $data=$execute->fetch_array(MYSQLI_ASSOC);
            ?>
            <div class="group-input">
                <label for="karyawan">Nama Supplier</label>
                <input class="form-custom" value="<?php echo $data['nama_karyawan'];?>" disabled type="text" autocomplete="off" required name="karyawan" id="karyawan">
            </div>
        </div>
        <div class="group-input">
            <?php
            $query="SELECT nama_karyawan FROM karyawan WHERE id_karyawan='$b'";
            $execute=$konek->query($query);
            $data=$execute->fetch_array(MYSQLI_ASSOC);
            ?>
            <div class="group-input">
                <label for="karyawan">Jenis Barang</label>
                <input class="form-custom" value="<?php echo $data['nama_karyawan'];?>" disabled type="text" autocomplete="off" required name="jenisbarang" id="jenisbarang" placeholder="jenisbarang">
            </div>
        </div>
        <?php
        $query="SELECT penilaian_id,kriteria_id FROM penilaian_kriteria INNER JOIN kriteria USING(id_kriteria) WHERE id_divisi='$a'";
        $execute=$konek->query($query);
        if ($execute->num_rows > 0){
            while($data=$execute->fetch_array(MYSQLI_ASSOC)){
                echo "<div class=\"group-input\">";
                echo "<label for=\"nilai\">$data[namaKriteria]</label>";
                echo "<input type='hidden' value=\"$data[id_nilaisupplier]\" name=\"id[]\">";
                echo "<select class=\"form-custom\" required name=\"nilai[]\" id=\"nilai\">";
                $query2="SELECT id_nilaikriteria,keterangan FROM nilai_kriteria WHERE id_kriteria='$data[id_kriteria]'";
                $execute2=$konek->query($query2);
                    if ($execute2->num_rows > 0){
                        while ($data2=$execute2->fetch_array(MYSQLI_ASSOC)){
                            if (array_search($data2['id_nilaikriteria'],$getData)) {
                                $selected="selected";
                            }else{
                                $selected=null;
                            }
                            echo "<option $selected value=\"$data2[id_nilaikriteria]\">$data2[keterangan]</option>";
                        }
                    }else{
                        echo "<option disabled value=\"\">Belum ada Nilai Kriteria</option>";
                    };
                echo "</select></div>";
            }
        }
        ?>
    </div>
    <div class="panel-bottom">
        <button type="submit" id="buttonsimpan" class="btn btn-green"><i class="fa fa-save"></i> Simpan</button>
        <button type="reset" id="buttonreset" class="btn btn-second">Reset</button>
    </div>
</form>