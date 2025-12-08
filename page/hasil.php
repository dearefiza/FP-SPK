<div class="panel">
    <div class="panel-middle" id="judul">
        <img src="asset/image/hasil.png" class="icon">
        <div id="judul-text">
            <h2 class="text-green">Hasil Perankingan (SAW)</h2>
            Halaman Perangkingan Kinerja Karyawan
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
    outline: none;
}

.btn-search:focus {
    border-color: #4c8bf5;
    box-shadow: 0 0 4px rgba(76,139,245,0.4);
}

.btn-pdf {
    background: #e63946;
    color: white;
    padding: 9px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.2s;
}

.btn-pdf:hover {
    background: #c82333;
}

/* ---- TABLE STYLING ---- */
.table-ranking {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
    border-radius: 10px;
    overflow: hidden;
}

.table-ranking thead tr {
    background: #eef2f5;
}

.table-ranking thead th {
    padding: 14px 12px;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #ddd;
    text-align: left;
}

.table-ranking tbody tr td {
    padding: 12px 12px;
    border-bottom: 1px solid #eee;
}

.table-ranking tbody tr:nth-child(even) {
    background: #fafafa;
}

.table-ranking tbody tr:hover {
    background: #f1f7ff;
}

.table-ranking td:last-child {
    text-align: right;
    font-weight: bold;
    color: #1a73e8;
}
</style>

<div class="panel">
    <div class="panel-middle">

        <!-- Bar Atas: Search + Export PDF -->
        <div class="action-bar">
            <input type="text" id="searchSAW" class="btn-search" placeholder="Cari karyawan / divisi...">

            <a href="./export_saw_pdf.php" class="btn-pdf" target="_blank">
                <i class="fa fa-file-pdf"></i> Export PDF
            </a>
        </div>

        <!-- Tabel Hasil SAW -->
        <table class="table-ranking" id="tableSAW">
            <thead>
                <tr>
                    <th style="width:80px;">Ranking</th>
                    <th>Nama Karyawan</th>
                    <th>Divisi</th>
                    <th style="width:140px; text-align:right;">Nilai Akhir</th>
                </tr>
            </thead>

            <tbody>
                <?php
                include './proses/proseshitung_saw.php';
                $rank = 1;
                foreach ($hasil_ranking as $row) {
                    echo "
                    <tr>
                        <td>$rank</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['divisi']}</td>
                        <td>{$row['hasil']}</td>
                    </tr>";
                    $rank++;
                }
                ?>
            </tbody>
        </table>

    </div>
</div>

<script>
// SEARCH FILTER
document.getElementById('searchSAW').addEventListener('keyup', function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tableSAW tbody tr');

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>
