<?php
include './proses/proseshitung_saw.php';
?>

<div class="panel">
    <div class="panel-middle" id="judul">
        <img src="asset/image/hasil.png" class="icon">
        <div id="judul-text">
            <h2 class="text-green">Hasil Perankingan (SAW)</h2>
            Halaman Perankingan Kinerja Karyawan
        </div>
    </div>
</div>

<style>
/* ---- SEARCH + EXPORT BUTTON ---- */
.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.btn-search {
    width: 60%;
    padding: 10px 15px;
    border-radius: 8px;
    border: 1px solid #d0d4d8;
    font-size: 14px;
}

.btn-pdf {
    background: #e63946;
    color: white;
    padding: 9px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
}

.table-ranking {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
    margin-top: 10px;
}

.table-ranking th, .table-ranking td {
    padding: 10px 12px;
    border: 1px solid #eee;
}

.table-ranking thead {
    background: #eef2f5;
    font-weight: bold;
}

.section-title {
    margin-top: 30px;
    font-size: 20px;
    font-weight: bold;
    color: #1a73e8;
    border-left: 6px solid #1a73e8;
    padding-left: 10px;
}

/* TOP 3 COLOR */
.top1-row { background: #fff4b8 !important; font-weight: bold; }
.top2-row { background: #e8e8e8 !important; font-weight: bold; }
.top3-row { background: #f7d7c4 !important; font-weight: bold; }

/* collapsible */
.collapse-box {
    margin-top: 10px;
}

.collapse-header {
    background: #1a73e8;
    color: white;
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    font-weight: bold;
}

.collapse-content {
    display: none;
    margin-top: 10px;
}
</style>

<div class="panel">
    <div class="panel-middle">

        <!-- ============ SEARCH BAR + PDF ============ -->
        <div class="action-bar">
            <input type="text" id="searchSAW" class="btn-search" placeholder="Cari karyawan / divisi...">

            <a href="./export_saw_pdf.php" class="btn-pdf" target="_blank">
                <i class="fa fa-file-pdf"></i> Export PDF
            </a>
        </div>





        <!-- ====================================================== -->
        <!-- ==================== RANKING ========================== -->
        <!-- ====================================================== -->
        <div class="section-title">Hasil Akhir Perankingan</div>

        <table class="table-ranking" id="tableSAW">
            <thead>
                <tr>
                    <th style="width: 80px;">Ranking</th>
                    <th>Nama Karyawan</th>
                    <th>Divisi</th>
                    <th>Nilai Akhir</th>
                </tr>
            </thead>

            <tbody>
            <?php
            $rank = 1;
            foreach ($hasil_ranking as $row):

                // Tentukan warna TOP 3
                $rowClass = "";
                if ($rank == 1) $rowClass = "top1-row";
                else if ($rank == 2) $rowClass = "top2-row";
                else if ($rank == 3) $rowClass = "top3-row";
            ?>
                <tr class="<?= $rowClass; ?>">
                    <td><?= $rank; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['divisi']; ?></td>
                    <td><b><?= $row['hasil']; ?></b></td>
                </tr>
            <?php
            $rank++;
            endforeach;
            ?>
            </tbody>
        </table>





        <!-- ====================================================== -->
        <!-- =========== DETAIL PERHITUNGAN SAW ==================== -->
        <!-- ====================================================== -->
        <div class="section-title">Detail Perhitungan SAW</div>

        <div class="collapse-box">
            <div class="collapse-header" onclick="toggleCollapse('wsaw')">
                ➕ Lihat Perhitungan Weighted Sum (W × R)
            </div>

            <div class="collapse-content" id="wsaw">
                <table class="table-ranking">
                    <thead>
                        <tr>
                            <th>Nama Karyawan</th>
                            <?php foreach ($kriteria as $k): ?>
                                <th><?= $k['nama_kriteria']; ?> (W = <?= $k['bobot']; ?>)</th>
                            <?php endforeach; ?>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($weighted_sum as $row): ?>
                        <tr>
                            <td><?= $row['nama']; ?></td>
                            <?php foreach ($row['wr'] as $w): ?>
                                <td><?= number_format($w, 4); ?></td>
                            <?php endforeach; ?>
                            <td><b><?= number_format($row['total'], 4); ?></b></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
// SEARCH FILTER (ranking only)
document.getElementById('searchSAW').addEventListener('keyup', function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tableSAW tbody tr');

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});

// COLLAPSE FUNCTION
function toggleCollapse(id) {
    let box = document.getElementById(id);
    box.style.display = (box.style.display === "block") ? "none" : "block";
}
</script>
