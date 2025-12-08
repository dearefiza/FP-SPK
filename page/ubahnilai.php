<?php
$id = $_GET['id']; // id_penilaian

// 1. AMBIL DATA PENILAIAN
$q = "
    SELECT p.id_penilaian, 
           k.id_karyawan, k.nama_karyawan,
           d.id_divisi, d.nama_divisi
    FROM penilaian p
    JOIN karyawan k ON p.karyawan_id = k.id_karyawan
    JOIN divisi d ON p.divisi_id = d.id_divisi
    WHERE p.id_penilaian = '$id'
";

$pen = $konek->query($q)->fetch_assoc();

if (!$pen) {
    echo "<script>alert('Data penilaian tidak ditemukan');window.location='index.php?page=penilaian';</script>";
    exit;
}
?>

<div class="panel-top panel-top-edit">
    <b><i class="fa fa-pencil-alt"></i> Ubah Penilaian</b>
</div>

<form id="form" method="POST" action="./proses/prosesubah.php">
    <input type="hidden" name="op" value="penilaian">
    <input type="hidden" name="id_penilaian" value="<?= $pen['id_penilaian'] ?>">

    <div class="panel-middle">

        <!-- Nama Karyawan -->
        <div class="group-input">
            <label>Nama Karyawan</label>
            <input type="text" class="form-custom" value="<?= $pen['nama_karyawan'] ?>" disabled>
        </div>

        <!-- Divisi -->
        <div class="group-input">
            <label>Divisi</label>
            <input type="text" class="form-custom" value="<?= $pen['nama_divisi'] ?>" disabled>
        </div>

        <hr><br>

        <h4>Nilai Kriteria</h4>

        <?php
        // 2. AMBIL DAFTAR KRITERIA + NILAI KARYAWAN SAAT INI
        $q_k = "
            SELECT k.id_kriteria, k.nama_kriteria, pk.nilai
            FROM kriteria k
            LEFT JOIN penilaian_kriteria pk 
                 ON pk.kriteria_id = k.id_kriteria AND pk.penilaian_id = '$id'
            ORDER BY k.id_kriteria
        ";

        $rs = $konek->query($q_k);
        while ($row = $rs->fetch_assoc()) {
        ?>
            <div class="group-input">
                <label><?= $row['nama_kriteria'] ?></label>
                <input type="number"
                       step="0.01"
                       class="form-custom"
                       name="kriteria[<?= $row['id_kriteria'] ?>]"
                       value="<?= $row['nilai'] ?>">
            </div>
        <?php } ?>

    </div>

    <div class="panel-bottom">
        <button type="submit" class="btn btn-green">
            <i class="fa fa-save"></i> Simpan
        </button>
        <a href="./index.php?page=penilaian" class="btn btn-second">Batal</a>
    </div>
</form>
