<?php
$id = htmlspecialchars(@$_GET['id']);
$query = "SELECT * FROM kriteria WHERE id_kriteria='$id'";
$sifat = array("Benefit","Cost");
$execute = $konek->query($query);

if ($execute->num_rows > 0){
    $data = $execute->fetch_array(MYSQLI_ASSOC);
} else {
    header('location:./?page=kriteria');
    exit();
}
?>

<div class="panel-top panel-top-edit">
    <b><i class="fa fa-pencil-alt"></i> Ubah data</b>
</div>

<form id="form" method="POST" action="./proses/prosesubah.php">
    <input type="hidden" name="op" value="kriteria">
    <input type="hidden" name="id" value="<?php echo $data['id_kriteria']; ?>">

    <div class="panel-middle">

        <div class="group-input">
            <label for="kriteria">Nama Kriteria :</label>
            <input type="text" value="<?php echo $data['nama_kriteria']; ?>" 
                   class="form-custom" required autocomplete="off" 
                   placeholder="Nama Kriteria" id="kriteria" name="kriteria">
        </div>

        <div class="group-input">
            <label for="sifat_kriteria">Sifat Kriteria :</label>
            <select class="form-custom" required id="sifat_kriteria_id" name="sifat_kriteria">
                <?php foreach ($sifat as $datasifat) { ?>
                    <option value="<?= $datasifat ?>" <?= ($datasifat == $data['sifat_kriteria_id']) ? 'selected' : '' ?>>
                        <?= $datasifat ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Tambahan Bobot -->
        <div class="group-input">
            <label for="bobot">Bobot kriteria :</label>
            <input 
                type="number" 
                step="0.01" 
                class="form-custom"
                required 
                value="<?php echo $data['bobot']; ?>" 
                autocomplete="off" 
                placeholder="Bobot kriteria (ex: 0.25)" 
                id="bobot" 
                name="bobot">
        </div>

    </div>

    <div class="panel-bottom">
        <button type="submit" id="buttonsimpan" class="btn btn-green">
            <i class="fa fa-save"></i> Simpan
        </button>
        <button type="reset" id="buttonreset" class="btn btn-second">Reset</button>
    </div>
</form>
