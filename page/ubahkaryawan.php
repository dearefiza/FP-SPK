<?php
$id = htmlspecialchars(@$_GET['id']);

// ambil data karyawan + id_divisi
$query = "SELECT id_karyawan, nama_karyawan, divisi_id FROM karyawan WHERE id_karyawan='$id'";
$execute = $konek->query($query);

if ($execute->num_rows > 0){
    $data = $execute->fetch_array(MYSQLI_ASSOC);
} else {
    header('location:./?page=karyawan');
    exit();
}

// ambil daftar divisi untuk dropdown
$qdiv = "SELECT * FROM divisi ORDER BY nama_divisi ASC";
$divisi = $konek->query($qdiv);
?>

<div class="panel-top panel-top-edit">
    <b><i class="fa fa-pencil-alt"></i> Ubah data</b>
</div>

<form id="form" method="POST" action="./proses/prosesubah.php">
    <input type="hidden" name="op" value="karyawan">
    <input type="hidden" name="id" value="<?php echo $data['id_karyawan']; ?>">

    <div class="panel-middle">

        <div class="group-input">
            <label for="karyawan">Nama Karyawan :</label>
            <input type="text" value="<?php echo $data['nama_karyawan']; ?>" 
                   class="form-custom" required autocomplete="off" 
                   placeholder="Nama Karyawan" id="karyawan" name="karyawan">
        </div>

        <div class="group-input" style="margin-top:10px;">
            <label for="divisi">Divisi :</label>
            <select class="form-custom" name="divisi" id="divisi" required>
                <option value="">-- Pilih Divisi --</option>

                <?php while($d = $divisi->fetch_assoc()) { ?>
                    <option value="<?= $d['id_divisi']; ?>" 
                        <?= ($data['divisi_id'] == $d['id_divisi']) ? 'selected' : ''; ?>>
                        <?= $d['nama_divisi']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

    </div>

    <div class="panel-bottom">
        <button type="submit" id="buttonsimpan" class="btn btn-green">
            <i class="fa fa-save"></i> Simpan
        </button>
        <button type="reset" id="buttonreset" class="btn btn-second">Reset</button>
    </div>
</form>
