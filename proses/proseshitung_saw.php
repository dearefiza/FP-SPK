<?php
// proseshitung_saw.php
// Tidak perlu require connect.php — sudah di-include dari index/admin

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
    return;
}

// ===============================
// 3. Cari MAX dan MIN setiap kolom K1–K5
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
// 4. Proses Normalisasi + Hitung Nilai SAW
// ===============================
$hasil_ranking = [];

foreach ($data as $row) {

    $totalSAW = 0;

    // Loop setiap kriteria K1–K5
    for ($i = 1; $i <= 5; $i++) {

        $nilai = (float)$row["K".$i];
        $bobot = (float)$kriteria[$i-1]['bobot'];

        // sifat_kriteria_id → 1 = Benefit, 2 = Cost
        $tipe = ($kriteria[$i-1]['sifat_kriteria_id'] == 1) ? 'benefit' : 'cost';

        // Normalisasi
        if ($tipe === 'benefit') {
            $normal = ($nilai > 0) ? $nilai / $maxmin[$i]['max'] : 0;
        } else { // COST
            $normal = ($nilai > 0) ? $maxmin[$i]['min'] / $nilai : 0;
        }

        // Hitung nilai terbobot
        $totalSAW += $normal * $bobot;
    }

    // Masukkan hasil
    $hasil_ranking[] = [
        'nama'   => $row['nama_karyawan'],
        'divisi' => $row['nama_divisi'],
        'hasil'  => round($totalSAW, 4)
    ];
}

// ===============================
// 5. Urutkan dari terbesar ke terkecil
// ===============================
usort($hasil_ranking, function($a, $b) {
    return $b['hasil'] <=> $a['hasil'];
});
