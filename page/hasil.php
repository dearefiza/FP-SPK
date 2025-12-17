<?php
include './proses/proseshitung_saw.php';

// Ambil kembali parameter sensitivitas untuk tampilan
$sens_k = isset($_GET['sens_k']) ? (int)$_GET['sens_k'] : 0;
$sens_p = isset($_GET['sens_p']) ? (int)$_GET['sens_p'] : 0;

// Fallback aman
$kriteria      = $kriteria      ?? [];
$hasil_ranking = $hasil_ranking ?? [];
$matriks_R     = $matriks_R     ?? [];
$weighted_sum  = $weighted_sum  ?? [];
$bobot_saw     = $bobot_saw     ?? [];

/**
 * Buat key unik untuk mapping total:
 * - kalau ada divisi => "nama||divisi"
 * - kalau tidak => "nama"
 */
function makeKey($nama, $divisi = null) {
    $nama = (string)$nama;
    $divisi = ($divisi !== null) ? (string)$divisi : '';
    return ($divisi !== '') ? ($nama . '||' . $divisi) : $nama;
}

// ===============================
// Map Total SAW dari DETAIL (weighted_sum)
// ===============================
$totalSAWMap = [];
$totalSAWNormMap = [];

foreach ($weighted_sum as $ws) {
    $nama   = $ws['nama'] ?? '';
    $divisi = $ws['divisi'] ?? null;

    $key = makeKey($nama, $divisi);

    // total dari detail (ΣW×R)
    $totalSAWMap[$key] = (float)($ws['total'] ?? 0);

    // kalau kamu punya total_norm dari proseshitung (optional)
    if (isset($ws['total_norm'])) {
        $totalSAWNormMap[$key] = (float)$ws['total_norm'];
    }
}
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
.action-bar{
  display:flex;justify-content:space-between;align-items:flex-start;
  margin-bottom:18px;gap:16px;flex-wrap:wrap;
}
.action-left{
  display:flex;flex-direction:column;gap:6px;max-width:420px;width:100%;
}
.btn-search{
  width:100%;max-width:400px;padding:8px 12px;border-radius:8px;
  border:1px solid #d0d4d8;font-size:14px;
}
.btn-pdf{
  background:#e63946;color:#fff;padding:9px 18px;border-radius:6px;
  text-decoration:none;font-size:14px;white-space:nowrap;margin-left:auto;margin-right:4px;
}

.table-ranking{width:100%;border-collapse:collapse;font-size:15px;margin-top:10px;}
.table-ranking th,.table-ranking td{padding:10px 12px;border:1px solid #eee;}
.table-ranking thead{background:#eef2f5;font-weight:bold;}

.section-title{
  margin-top:30px;font-size:20px;font-weight:bold;color:#1a73e8;
  border-left:6px solid #1a73e8;padding-left:10px;
}

.top1-row{background:#fff4b8 !important;font-weight:bold;}
.top2-row{background:#e8e8e8 !important;font-weight:bold;}
.top3-row{background:#f7d7c4 !important;font-weight:bold;}

.collapse-box{margin-top:10px;}
.collapse-header{
  background:#1a73e8;color:#fff;padding:10px 14px;border-radius:6px;
  font-size:16px;cursor:pointer;font-weight:bold;
}
.collapse-content{display:none;margin-top:10px;}

.sens-row{
  display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-top:4px;
  font-size:13px;max-width:400px;
}
.sens-row label{font-weight:500;color:#444;}
.sens-select{
  padding:6px 10px;border-radius:6px;border:1px solid #d0d4d8;
  font-size:13px;background:#fff;min-width:120px;
}
.small-note{font-size:12px;color:#555;margin-top:6px;}
.empty-box{
  padding:14px;background:#fff6f6;border:1px solid #ffd1d1;border-radius:10px;
  color:#7a1c1c;margin-top:12px;
}
</style>

<div class="panel">
    <div class="panel-middle">

        <!-- SEARCH + SENSITIVITY + PDF -->
        <div class="action-bar">
            <div class="action-left">
                <input type="text" id="searchSAW" class="btn-search" placeholder="Cari karyawan / divisi...">

                <div class="sens-row">
                    <label>Pengujian sensitivitas:</label>

                    <select id="sensKriteria" class="sens-select">
                        <option value="0">Tanpa pengujian (default)</option>
                        <?php foreach ($kriteria as $k): ?>
                            <option value="<?= (int)$k['id_kriteria']; ?>"
                                <?= ($sens_k == (int)$k['id_kriteria']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($k['nama_kriteria']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select id="sensPersen" class="sens-select">
                        <option value="0" <?= ($sens_p == 0) ? 'selected' : ''; ?>>0% (tidak diubah)</option>
                        <?php
                        $opsi = [];
                        for ($p = 20; $p <= 300; $p += 20) { $opsi[] = -$p; $opsi[] = $p; }
                        sort($opsi);
                        foreach ($opsi as $p):
                        ?>
                            <option value="<?= (int)$p; ?>" <?= ($sens_p == (int)$p) ? 'selected' : ''; ?>>
                                <?= ($p > 0 ? '+' : '') . (int)$p; ?>%
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if ($sens_k > 0 && $sens_p != 0 && isset($kriteria[$sens_k-1])): ?>
                    <div class="small-note">
                        Skenario uji: bobot <b><?= htmlspecialchars($kriteria[$sens_k-1]['nama_kriteria']); ?></b>
                        diubah sebesar <b><?= ($sens_p > 0 ? '+' : '') . (int)$sens_p; ?>%</b>.
                    </div>
                <?php endif; ?>
            </div>

            <a href="./export_saw_pdf.php" class="btn-pdf" target="_blank">
                <i class="fa fa-file-pdf"></i> Export PDF
            </a>
        </div>

        <?php if (empty($hasil_ranking)): ?>
            <div class="empty-box">
                Data penilaian belum tersedia, jadi perankingan SAW belum bisa ditampilkan.
            </div>
        <?php else: ?>

            <!-- ==================== RANKING ==================== -->
            <div class="section-title">Hasil Akhir Perankingan</div>

            <div class="collapse-header" onclick="toggleCollapse('rankingSAW')">
                ➕ Lihat Hasil Akhir Perankingan
            </div>

            <div class="collapse-content" id="rankingSAW" style="display:block;">
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
                        $rowClass = "";
                        if ($rank == 1)      $rowClass = "top1-row";
                        else if ($rank == 2) $rowClass = "top2-row";
                        else if ($rank == 3) $rowClass = "top3-row";
                    ?>
                        <tr class="<?= $rowClass; ?>">
                            <td><?= $rank; ?></td>
                            <td><?= htmlspecialchars($row['nama']); ?></td>
                            <td><?= htmlspecialchars($row['divisi']); ?></td>
                            <td><b><?= number_format((float)$row['hasil'], 4); ?></b></td>
                        </tr>
                    <?php
                        $rank++;
                    endforeach;
                    ?>
                    </tbody>
                </table>
            </div>

            <!-- ==================== DETAIL ==================== -->
            <div class="section-title">Detail Perhitungan SAW</div>

            <!-- ============ NORMALISASI (R) + TOTAL dari DETAIL (ΣW×R) ============ -->
            <div class="collapse-box">
                <div class="collapse-header" onclick="toggleCollapse('normR')">
                    ➕ Lihat Normalisasi (R)
                </div>

                <div class="collapse-content" id="normR">
                    <table class="table-ranking">
                        <thead>
                            <tr>
                                <th>Nama Karyawan</th>
                                <?php foreach ($kriteria as $i => $k): ?>
                                    <?php
                                        $wShow = isset($bobot_saw[$i]) ? (float)$bobot_saw[$i] : (float)($k['bobot'] ?? 0);
                                    ?>
                                    <th><?= htmlspecialchars($k['nama_kriteria']); ?> (W = <?= number_format($wShow, 2); ?>)</th>
                                <?php endforeach; ?>
                                <th>Total SAW</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($matriks_R as $row): ?>
                            <?php
                                $nama   = $row['nama'] ?? '';
                                $divisi = $row['divisi'] ?? null; // kalau tidak ada, tetap aman
                                $key    = makeKey($nama, $divisi);
                                $totalSAW = $totalSAWMap[$key] ?? ($totalSAWMap[$nama] ?? 0);
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($nama); ?></td>

                                <?php foreach (($row['nilai'] ?? []) as $r): ?>
                                    <td><?= number_format((float)$r, 4); ?></td>
                                <?php endforeach; ?>

                                <td><b><?= number_format((float)$totalSAW, 4); ?></b></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="small-note">
                    </div>
                </div>
            </div>

            <!-- ============ WEIGHTED SUM (W × R) ============ -->
            <div class="collapse-box">
                <div class="collapse-header" onclick="toggleCollapse('wsaw')">
                    ➕ Lihat Perhitungan Weighted Sum (W × R)
                </div>

                <div class="collapse-content" id="wsaw">
                    <table class="table-ranking">
                        <thead>
                            <tr>
                                <th>Nama Karyawan</th>

                                <?php foreach ($kriteria as $i => $k): ?>
                                    <?php
                                        $wShow = isset($bobot_saw[$i]) ? (float)$bobot_saw[$i] : (float)($k['bobot'] ?? 0);
                                    ?>
                                    <th><?= htmlspecialchars($k['nama_kriteria']); ?> (W = <?= number_format($wShow, 4); ?>)</th>
                                <?php endforeach; ?>

                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($weighted_sum as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <?php foreach ($row['wr'] as $w): ?>
                                    <td><?= number_format((float)$w, 4); ?></td>
                                <?php endforeach; ?>
                                <td><b><?= number_format((float)$row['total'], 4); ?></b></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<script>
// SEARCH FILTER (ranking only)
document.getElementById('searchSAW')?.addEventListener('keyup', function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tableSAW tbody tr');

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});

// COLLAPSE
function toggleCollapse(id) {
    let box = document.getElementById(id);
    if (!box) return;
    box.style.display = (box.style.display === "block") ? "none" : "block";
}

// SENSITIVITY DROPDOWN
document.addEventListener('DOMContentLoaded', function () {
    const sensKriteria = document.getElementById('sensKriteria');
    const sensPersen   = document.getElementById('sensPersen');

    function updateSensitivity() {
        const params = new URLSearchParams(window.location.search);

        // pastikan tetap di halaman hasil (ganti kalau router kamu beda)
        params.set('page', 'hasil');

        const k = sensKriteria?.value || '0';
        const p = sensPersen?.value || '0';

        if (k !== '0') params.set('sens_k', k);
        else params.delete('sens_k');

        if (p !== '0') params.set('sens_p', p);
        else params.delete('sens_p');

        window.location.href = '?' + params.toString();
    }

    sensKriteria?.addEventListener('change', updateSensitivity);
    sensPersen?.addEventListener('change', updateSensitivity);
});
</script>
