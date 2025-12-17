<?php
// =====================================================
// AHP KALKULATOR (INPUT MANUAL FULL) + APPLY BOBOT KE DB
// - Semua sel off-diagonal diisi manual (atas & bawah)
// - Diagonal otomatis = 1 (readonly)
// - Perhitungan: normalisasi kolom -> average baris (bobot)
// - Uji konsistensi: lambda max, CI, CR (Saaty)
// - Tambahan: tombol "Terapkan Bobot" -> update tabel kriteria.bobot
// =====================================================

$hasil  = false;
$errors = [];

// =====================================================
// 0) Ambil kriteria dari DB (untuk label & mapping update bobot)
// =====================================================
$kriteria = $konek->query("SELECT * FROM kriteria ORDER BY id_kriteria ASC")->fetch_all(MYSQLI_ASSOC);
$n = count($kriteria); // agar otomatis sesuai jumlah kriteria di DB (kamu sekarang 5)

// kalau kamu MAU tetap pakai 5 fix, uncomment ini:
// $n = 5;

$RI = [
    1  => 0.00,
    2  => 0.00,
    3  => 0.58,
    4  => 0.90,
    5  => 1.12,
    6  => 1.24,
    7  => 1.32,
    8  => 1.41,
    9  => 1.45,
    10 => 1.49
];

function e($s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function parse_ahp_value($v): ?float {
    if ($v === null) return null;
    $s = trim((string)$v);
    if ($s === '') return null;

    $s = str_replace(',', '.', $s);

    if (strpos($s, '/') !== false) {
        [$a, $b] = array_map('trim', explode('/', $s, 2));
        if (!is_numeric($a) || !is_numeric($b)) return null;
        $b = (float)$b;
        if ($b == 0.0) return null;
        $val = (float)$a / $b;
        return ($val > 0) ? $val : null;
    }

    if (!is_numeric($s)) return null;
    $val = (float)$s;
    return ($val > 0) ? $val : null;
}

// =====================================================
// Trigger hitung jika klik Hitung AHP ATAU Terapkan Bobot
// =====================================================
$isHitung = isset($_POST['hitung']);
$isApply  = isset($_POST['apply_bobot']);

if ($isHitung || $isApply) {

    // ===== 1) Ambil matriks dari input (TIDAK resiprokal otomatis) =====
    $m = array_fill(0, $n, array_fill(0, $n, 0.0));

    for ($i=0; $i<$n; $i++) {
        for ($j=0; $j<$n; $j++) {

            if ($i == $j) {
                $m[$i][$j] = 1.0;
                continue;
            }

            $val = parse_ahp_value($_POST['m'][$i][$j] ?? null);
            if ($val === null || $val <= 0) {
                $errors[] = "Nilai ".($kriteria[$i]['nama_kriteria'] ?? ('K'.($i+1)))." vs ".($kriteria[$j]['nama_kriteria'] ?? ('K'.($j+1)))." tidak valid (isi > 0, contoh: 3 / 1/3 / 0,25).";
                $val = 1.0; // fallback
            }
            $m[$i][$j] = $val;
        }
    }

    if (empty($errors)) {
        $hasil = true;

        // ===== 2) Jumlah kolom =====
        $sumCol = array_fill(0, $n, 0.0);
        for ($j=0; $j<$n; $j++) {
            $s = 0.0;
            for ($i=0; $i<$n; $i++) $s += $m[$i][$j];
            $sumCol[$j] = $s;
        }

        // ===== 3) Normalisasi & Bobot =====
        $norm  = array_fill(0, $n, array_fill(0, $n, 0.0));
        $bobot = array_fill(0, $n, 0.0);

        for ($i=0; $i<$n; $i++) {
            $rowTotal = 0.0;
            for ($j=0; $j<$n; $j++) {
                $norm[$i][$j] = ($sumCol[$j] == 0.0) ? 0.0 : ($m[$i][$j] / $sumCol[$j]);
                $rowTotal += $norm[$i][$j];
            }
            $bobot[$i] = $rowTotal / $n;
        }

        // Normalisasi ulang bobot biar sum=1 (lebih aman)
        $sumBobotTmp = array_sum($bobot);
        if ($sumBobotTmp > 0) {
            for ($i=0; $i<$n; $i++) $bobot[$i] = $bobot[$i] / $sumBobotTmp;
        }

        // ===== 4) A*w dan Lambda per baris =====
        $Aw     = array_fill(0, $n, 0.0);
        $lambda = array_fill(0, $n, 0.0);

        for ($i=0; $i<$n; $i++) {
            $s = 0.0;
            for ($j=0; $j<$n; $j++) $s += $m[$i][$j] * $bobot[$j];
            $Aw[$i] = $s;
            $lambda[$i] = ($bobot[$i] == 0.0) ? 0.0 : ($Aw[$i] / $bobot[$i]);
        }

        // ===== TOTAL sesuai Excel =====
        $sumBobot = array_sum($bobot);                // = 1.00
        $sumAw    = array_sum($Aw);                   // total Matriks x Bobot
        $totalLambdaExcel = ($sumBobot == 0.0) ? 0.0 : ($sumAw / $sumBobot);

        // ===== 5) CI & CR (λ max versi Excel) =====
        $lambdaMax = $totalLambdaExcel;
        $CI = ($lambdaMax - $n) / ($n - 1);
        $CR = ($RI[$n] == 0.0) ? 0.0 : ($CI / $RI[$n]);

        // ===== Total bawah (kolom normalisasi harus jadi 1.00) =====
        $normColSum = array_fill(0, $n, 0.0);
        for ($j=0; $j<$n; $j++) {
            $s = 0.0;
            for ($i=0; $i<$n; $i++) $s += $norm[$i][$j];
            $normColSum[$j] = $s;
        }

        // =====================================================
        // 6) APPLY: simpan bobot ke tabel kriteria jika KONSISTEN
        // =====================================================
        if ($isApply) {
            if ($CR > 0.1) {
                $errors[] = "CR = ".number_format($CR, 6, '.', '')." (TIDAK KONSISTEN). Bobot tidak disimpan.";
            } else {
                // Update DB (kriteria.bobot) sesuai urutan ORDER BY id_kriteria
                $konek->begin_transaction();
                try {
                    $stmt = $konek->prepare("UPDATE kriteria SET bobot = ? WHERE id_kriteria = ?");

                    for ($i=0; $i<$n; $i++) {
                        $id = (int)$kriteria[$i]['id_kriteria'];
                        $w  = (float)$bobot[$i];          // bobot hasil AHP
                        $stmt->bind_param("di", $w, $id);
                        $stmt->execute();
                    }

                    $stmt->close();
                    $konek->commit();

                    echo "<script>
                        alert('Bobot AHP berhasil diterapkan ke halaman Kriteria!');
                        window.location.href = 'index.php?page=kriteria';
                    </script>";
                    exit;

                } catch (Throwable $e) {
                    $konek->rollback();
                    $errors[] = "Gagal menyimpan bobot: ".$e->getMessage();
                }
            }
        }
    }
}
?>

<div class="panel">
  <div class="panel-middle">
    <h2 class="text-green">ANALYTICAL HIERARCHY PROCESS (AHP)</h2>
  </div>
</div>

<?php if (!empty($errors)): ?>
<div class="panel">
  <div class="panel-top"><b class="text-red">Error</b></div>
  <div class="panel-middle">
    <ul>
      <?php foreach ($errors as $er): ?>
        <li><?= e($er) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php endif; ?>

<form method="POST">
  <div class="panel">
    <div class="panel-top"><b class="text-green">Matriks Perbandingan Berpasangan</b></div>
    <div class="panel-middle">

      <table>
        <tr>
          <th></th>
          <?php for ($j=0;$j<$n;$j++): ?>
            <th><?= e($kriteria[$j]['nama_kriteria'] ?? ('K'.($j+1))) ?></th>
          <?php endfor; ?>
        </tr>

        <?php for ($i=0;$i<$n;$i++): ?>
          <tr>
            <td><b><?= e($kriteria[$i]['nama_kriteria'] ?? ('K'.($i+1))) ?></b></td>
            <?php for ($j=0;$j<$n;$j++): ?>
              <td>
                <?php
                  if ($i == $j) {
                      $val = 1;
                      $readonly = true;
                      $required = false;
                  } else {
                      $val = $_POST['m'][$i][$j] ?? '';
                      $readonly = false;
                      $required = true;
                  }
                ?>
                <input type="text"
                  name="m[<?= $i ?>][<?= $j ?>]"
                  value="<?= e($val) ?>"
                  <?= $readonly ? 'readonly' : '' ?>
                  <?= $required ? 'required' : '' ?>>
              </td>
            <?php endfor; ?>
          </tr>
        <?php endfor; ?>

        <?php if ($hasil): ?>
          <tr>
            <td><b>JUMLAH</b></td>
            <?php for ($j=0;$j<$n;$j++): ?>
              <td><b><?= number_format($sumCol[$j], 2, '.', '') ?></b></td>
            <?php endfor; ?>
          </tr>
        <?php endif; ?>
      </table>

      <button type="submit" name="hitung">Hitung AHP</button>

      <?php if ($hasil): ?>
        <button type="submit" name="apply_bobot" value="1" style="margin-left:10px;">
          Terapkan Bobot AHP ke Kriteria
        </button>
      <?php endif; ?>

    </div>
  </div>
</form>

<?php if ($hasil): ?>

<div class="panel">
  <div class="panel-top"><b class="text-green">Matriks Normalisasi</b></div>
  <div class="panel-middle">

    <table>
      <tr>
        <th></th>
        <?php for ($j=0;$j<$n;$j++): ?><th><?= e($kriteria[$j]['nama_kriteria'] ?? ('K'.($j+1))) ?></th><?php endfor; ?>
        <th>AVERAGE</th>
        <th>Matriks x Bobot</th>
        <th>Nilai Lambda</th>
      </tr>

      <?php for ($i=0;$i<$n;$i++): ?>
        <tr>
          <td><b><?= e($kriteria[$i]['nama_kriteria'] ?? ('K'.($i+1))) ?></b></td>
          <?php for ($j=0;$j<$n;$j++): ?>
            <td><?= number_format($norm[$i][$j], 2, '.', '') ?></td>
          <?php endfor; ?>
          <td><b><?= number_format($bobot[$i], 2, '.', '') ?></b></td>
          <td><?= number_format($Aw[$i], 9, '.', '') ?></td>
          <td><?= number_format($lambda[$i], 9, '.', '') ?></td>
        </tr>
      <?php endfor; ?>

      <tr>
        <td><b>TOTAL</b></td>
        <?php for ($j=0;$j<$n;$j++): ?>
          <td><b><?= number_format($normColSum[$j], 2, '.', '') ?></b></td>
        <?php endfor; ?>
        <td><b><?= number_format($sumBobot, 2, '.', '') ?></b></td>
        <td><b><?= number_format($sumAw, 9, '.', '') ?></b></td>
        <td><b><?= number_format($totalLambdaExcel, 9, '.', '') ?></b></td>
      </tr>
    </table>

  </div>
</div>

<div class="panel">
  <div class="panel-top"><b class="text-green">Uji Konsistensi</b></div>
  <div class="panel-middle">
    <table>
      <tr><td><b>AVG NILAI LAMDA (λ max)</b></td><td><b><?= number_format($lambdaMax, 9, '.', '') ?></b></td></tr>
      <tr><td>CI</td><td><?= number_format($CI, 9, '.', '') ?></td></tr>
      <tr><td>RI</td><td><?= number_format($RI[$n], 2, '.', '') ?></td></tr>
      <tr><td><b>CR</b></td><td><b><?= number_format($CR, 9, '.', '') ?></b></td></tr>
      <tr>
        <td colspan="2" align="center">
          <b class="<?= $CR<=0.1?'text-green':'text-red' ?>">
            <?= $CR<=0.1 ? 'KONSISTEN' : 'TIDAK KONSISTEN' ?>
          </b>
        </td>
      </tr>
    </table>
  </div>
</div>

<?php endif; ?>
