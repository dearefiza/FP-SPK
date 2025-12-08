<?php
$id = htmlspecialchars(@$_GET['id']);
$query = "SELECT * FROM kriteria WHERE id_kriteria='$id'";
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
    <input type="hidden" name="id" value="<?= $data['id_kriteria']; ?>">

    <div class="panel-middle">

        <!-- NAMA KRITERIA -->
        <div class="group-input">
            <label for="nama_kriteria">Nama Kriteria :</label>
            <input 
                type="text"
                class="form-custom"
                required
                id="nama_kriteria"
                name="nama_kriteria"
                value="<?= $data['nama_kriteria']; ?>"
                placeholder="Nama Kriteria">
        </div>

        <!-- SIFAT KRITERIA -->
        <div class="group-input">
            <label for="sifat_kriteria_id">Sifat Kriteria :</label>
            <select class="form-custom" id="sifat_kriteria_id" name="sifat_kriteria_id" required>
                <option value="1" <?= ($data['sifat_kriteria_id'] == 1 ? 'selected' : '') ?>>Benefit</option>
                <option value="2" <?= ($data['sifat_kriteria_id'] == 2 ? 'selected' : '') ?>>Cost</option>
            </select>
        </div>

        <!-- BOBOT -->
        <div class="group-input">
            <label for="bobot">Bobot Kriteria :</label>
            <input 
                type="number"
                step="0.01"
                class="form-custom"
                required
                id="bobot"
                name="bobot"
                value="<?= $data['bobot']; ?>"
                placeholder="Bobot kriteria (ex: 0.25)">
        </div>

    </div>

    <div class="panel-bottom">
        <button type="submit" id="buttonsimpan" class="btn btn-green">
            <i class="fa fa-save"></i> Simpan
        </button>
        <button type="reset" id="buttonreset" class="btn btn-second">Reset</button>
    </div>
</form>
