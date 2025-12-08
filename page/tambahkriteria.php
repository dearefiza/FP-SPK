<div class="panel-top">
    <b class="text-green"><i class="fa fa-plus-circle text-green"></i> Tambah data</b>
</div>

<form id="form" method="POST" action="./proses/prosestambah.php">
    <input type="hidden" name="op" value="kriteria">

    <div class="panel-middle">

        <!-- Nama Kriteria -->
        <div class="group-input">
            <label for="nama_kriteria">Nama kriteria :</label>
            <input 
                type="text" 
                class="form-custom" 
                required 
                autocomplete="off" 
                placeholder="Nama kriteria" 
                id="nama_kriteria" 
                name="nama_kriteria">
        </div>

        <!-- Sifat Kriteria -->
        <div class="group-input">
            <label for="id_sifat">Sifat kriteria :</label>
            <select class="form-custom" id="sifat_kriteria_id" name="sifat_kriteria_id">
                <option selected disabled>-- Pilih Sifat Kriteria --</option>
                <option value="1">Benefit</option>
                <option value="2">Cost</option>
            </select>
        </div>


        <!-- Bobot Kriteria -->
        <div class="group-input">
            <label for="bobot">Bobot kriteria :</label>
            <input 
                type="number" 
                step="0.01" 
                class="form-custom" 
                required 
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
