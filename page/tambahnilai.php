<div class="panel-top">
    <b class="text-green">Tambah Penilaian</b>
</div>

<form id="form" method="POST" action="./proses/prosestambah.php">
    <input type="hidden" name="op" value="penilaian">
    <input type="hidden" id="karyawan_id" name="karyawan_id">
    <input type="hidden" id="divisi_id" name="divisi_id">

    <div class="panel-middle">
        <!-- Input Nama Karyawan dengan Autocomplete -->
        <div class="group-input input-dropdown">
            <label for="karyawan">Nama Karyawan :</label>
            <input type="text" class="form-custom" required autocomplete="off"
                   placeholder="Ketik nama karyawan..." id="karyawan" name="karyawan">
            <ul class="dropdown" id="suggestions"></ul>
        </div>

        <!-- Divisi Otomatis Terisi (Readonly) -->
        <div class="group-input">
            <label for="divisi_display">Divisi :</label>
            <input type="text" class="form-custom" id="divisi_display" name="divisi_display" 
                   readonly style="background-color: #f0f0f0; cursor: not-allowed;" 
                   placeholder="Akan terisi otomatis">
        </div>

        <!-- Input Nilai Kriteria -->
        <?php
        $qkriteria = "SELECT * FROM kriteria ORDER BY id_kriteria";
        $reskriteria = $konek->query($qkriteria);
        while ($k = $reskriteria->fetch_assoc()) {
        ?>
        <div class="group-input">
            <label for="kriteria_<?= $k['id_kriteria'] ?>"><?= $k['nama_kriteria'] ?> :</label>
            <input type="number" class="form-custom" id="kriteria_<?= $k['id_kriteria'] ?>" 
                   name="kriteria[<?= $k['id_kriteria'] ?>]" step="0.01" min="0" max="100" 
                   placeholder="Masukkan nilai (0-100)" required>
        </div>
        <?php } ?>
    </div>

    <div class="panel-bottom">
        <button type="submit" id="buttonsimpan" class="btn btn-green">Simpan</button>
        <button type="reset" id="buttonreset" class="btn btn-second">Reset</button>
    </div>
</form>

<script src="js/autocomplete.js"></script>