<div class="panel-top">
    <b class="text-green">Tambah Penilaian</b>
</div>

<form id="form" method="POST" action="../proses/prosestambah.php">
    <input type="hidden" name="op" value="penilaian">
    <input type="hidden" id="karyawan_id" name="karyawan_id">
    <input type="hidden" id="divisi_id" name="divisi_id">

    <div class="panel-middle">
        <div class="group-input">
            <label for="id_karyawan_input">ID Karyawan :</label>
            <input type="number" class="form-custom" required autocomplete="off" placeholder="Masukkan ID karyawan..."
                id="id_karyawan_input" name="id_karyawan_input" min="1">
        </div>

        <div class="group-input">
            <label for="nama_karyawan_display">Nama Karyawan :</label>
            <input type="text" class="form-custom" id="nama_karyawan_display" name="nama_karyawan_display" readonly
                style="background-color: #f0f0f0; cursor: not-allowed;" placeholder="Akan terisi otomatis">
        </div>

        <div class="group-input">
            <label for="divisi_display">Divisi :</label>
            <input type="text" class="form-custom" id="divisi_display" name="divisi_display" readonly
                style="background-color: #f0f0f0; cursor: not-allowed;" placeholder="Akan terisi otomatis">
        </div>

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

<script src="./asset/js/karyawan_by_id.js"></script>