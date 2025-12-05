<div class="panel-top">
    <b class="text-green"><i class="fa fa-plus-circle text-green"></i> Tambah data</b>
</div>

<form id="form" method="POST" action="./proses/prosestambah.php">
    <input type="hidden" name="op" value="divisi">
    <div class="panel-middle">
        <div class="group-input">
            <label>Nama Karyawan :</label>
            <input list="listkaryawan" class="form-custom" autocomplete="off" placeholder="Cari Nama Karyawan" id="karyawan" name="karyawan">
            <datalist id="listkaryawan"></datalist>
        </div>
        <div class="group-input">
            <label>Divisi :</label>
            <input type="text" class="form-custom" placeholder="Divisi" id="divisi" name="divisi" readonly>
        </div>
    </div>
    <div class="panel-bottom">
        <button type="button" id="buttonsimpan" class="btn btn-green"><i class="fa fa-save"></i> Simpan</button>
        <button type="reset" id="buttonreset" class="btn btn-second">Reset</button>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// input autocomplete
$("#karyawan").on("input", function() {
    let keyword = $(this).val();
    $.ajax({
        url: "./proses/searchkaryawan.php",
        type: "GET",
        data: { keyword: keyword },
        success: function(data) {
            $("#listkaryawan").html(data);
        }
    });
});

// isi otomatis divisi setelah pilih nama
$("#karyawan").on("change", function() {
    let karyawan = $(this).val();
    $.ajax({
        url: "./proses/getdivisi.php",
        type: "GET",
        data: { nama_karyawan: karyawan }, // harus sama dengan PHP
        success: function(res) {
            $("#divisi").val(res);
        }
    });
});
</script>
