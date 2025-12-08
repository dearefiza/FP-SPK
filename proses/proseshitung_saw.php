<?php
// ===============================
// 1. Ambil data kriteria
// ===============================
$kriteria = $konek->query("SELECT * FROM kriteria ORDER BY id_kriteria ASC")
                  ->fetch_all(MYSQLI_ASSOC);

// ===============================
// 2. Ambil data penilaian (K1–K5 per karyawan)
// ===============================
$query = "
    SELECT 
        p.id_penilaian,
        k.nama_karyawan,
        d.nama_divisi,
        MAX(CASE WHEN pk.kriteria_id = 1 THEN pk.nilai END) AS K1,
        MAX(CASE WHEN pk.kriteria_id = 2 THEN pk.nilai END) AS K2,
        MAX(CASE WHEN pk.kriteria_id = 3 THEN pk.nilai END) AS K3,
        MAX(CASE WHEN pk.kriteria_id = 4 THEN pk.nilai END) AS K4,
        MAX(CASE WHEN pk.kriteria_id = 5 THEN pk.nilai END) AS K5
    FROM penilaian p
    JOIN karyawan k ON p.karyawan_id = k.id_karyawan
    JOIN divisi d ON p.divisi_id = d.id_divisi
    LEFT JOIN penilaian_kriteria pk ON p.id_penilaian = pk.penilaian_id
    GROUP BY p.id_penilaian, k.nama_karyawan, d.nama_divisi
";

$result = $konek->query($query);
$data   = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// ===============================
// Kalau belum ada penilaian
// ===============================
if (empty($data)) {
    $hasil_ranking = [];
    $matriks_X = [];
    $matriks_R = [];
    $weighted_sum = [];
    return;
}

// ===============================
// 3. Buat Matriks Keputusan Awal (X)
// ===============================
$matriks_X = [];
foreach ($data as $row) {
    $matriks_X[] = [
        'nama'  => $row['nama_karyawan'],
        'divisi'=> $row['nama_divisi'],
        'nilai' => [
            $row['K1'], $row['K2'], $row['K3'], $row['K4'], $row['K5']
        ]
    ];
}

// ===============================
// 4. Cari MAX dan MIN setiap kolom K1–K5
// ===============================
$maxmin = [];
for ($i = 1; $i <= 5; $i++) {
    $nilaiKolom = array_column($data, "K".$i);
    $maxmin[$i] = [
        'max' => max($nilaiKolom),
        'min' => min($nilaiKolom)
    ];
}

// ===============================
// 5. Proses Normalisasi (R) + Weighted Sum
// ===============================
$matriks_R = [];
$weighted_sum = [];
$hasil_ranking = [];

foreach ($data as $row) {

    $normRow = [];   // menyimpan nilai normalisasi
    $wrRow   = [];   // menyimpan nilai (W × R)
    $totalSAW = 0;

    // Loop kriteria K1–K5
    for ($i = 1; $i <= 5; $i++) {

        $nilai = (float)$row["K".$i];
        $bobot = (float)$kriteria[$i-1]['bobot'];

        $tipe = ($kriteria[$i-1]['sifat_kriteria_id'] == 1) ? 'benefit' : 'cost';

        // Normalisasi
        if ($tipe === 'benefit') {
            $normal = ($nilai > 0) ? $nilai / $maxmin[$i]['max'] : 0;
        } else { 
            $normal = ($nilai > 0) ? $maxmin[$i]['min'] / $nilai : 0;
        }

        // W × R
        $wr = $normal * $bobot;
        $totalSAW += $wr;

        $normRow[] = $normal;
        $wrRow[]   = $wr;
    }

    // Masukkan ke matriks Normalisasi (R)
    $matriks_R[] = [
        'nama'  => $row['nama_karyawan'],
        'nilai' => $normRow
    ];

    // Masukkan ke Weighted Sum
    $weighted_sum[] = [
        'nama'  => $row['nama_karyawan'],
        'wr'    => $wrRow,
        'total' => round($totalSAW, 4)
    ];

    // Masukkan ke Ranking
    $hasil_ranking[] = [
        'nama'   => $row['nama_karyawan'],
        'divisi' => $row['nama_divisi'],
        'hasil'  => round($totalSAW, 4)
    ];
}

// ===============================
// 6. Urutkan Ranking
// ===============================
usort($hasil_ranking, function($a, $b) {
    return $b['hasil'] <=> $a['hasil'];
});
