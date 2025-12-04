<form id="form" method="POST" action="./proses/prosestambah.php">
    <!-- WAJIB: harus sama dengan case di switch -->
    <input type="hidden" name="op" value="karyawan">

    <div class="panel-middle">
        <div class="group-input">
            <label for="karyawan">Nama Karyawan :</label>
            <input type="text" class="form-custom" required autocomplete="off"
                   placeholder="Nama Karyawan" id="karyawan" name="karyawan">
        </div>

        <div class="group-input">
            <label for="divisi">Divisi :</label>
            <select class="form-custom" id="divisi" name="divisi" required>
                <option value="">-- Pilih Divisi --</option>
                <?php
                $qdiv = "SELECT * FROM divisi ORDER BY nama_divisi";
                $resdiv = $konek->query($qdiv);
                while ($d = $resdiv->fetch_assoc()) {
                    echo "<option value='".$d['id_divisi']."'>".$d['nama_divisi']."</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="panel-bottom">
        <button type="submit" id="buttonsimpan" class="btn btn-green">
            Simpan
        </button>
        <button type="reset" id="buttonreset" class="btn btn-second">Reset</button>
    </div>
</form>
