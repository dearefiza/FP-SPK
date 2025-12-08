<?php
// Hitung data untuk statistik
$jml_divisi = $konek->query("SELECT COUNT(*) AS jml FROM divisi")->fetch_assoc()['jml'];
$jml_karyawan = $konek->query("SELECT COUNT(*) AS jml FROM karyawan")->fetch_assoc()['jml'];
$jml_kriteria = $konek->query("SELECT COUNT(*) AS jml FROM kriteria")->fetch_assoc()['jml'];
$jml_penilaian = $konek->query("SELECT COUNT(*) AS jml FROM penilaian")->fetch_assoc()['jml'];
?>

<!-- HEADER WELCOME -->
<div class="panel"
     style="border-radius:18px;background:#ffffff;border:1px solid #e5e7eb;
            box-shadow:0 4px 14px rgba(0,0,0,0.06);padding:28px 28px;margin-bottom:25px">

    <h1 style="font-size:26px;color:#1e3a8a;font-weight:700;margin-bottom:6px;">
        👋 Selamat Datang, Administrator
    </h1>

    <p style="font-size:15px;color:#475569;margin-top:4px;">
        Sistem Pendukung Keputusan Penilaian Kinerja Karyawan Startup<br>
        Menggunakan metode <b style="color:#1d4ed8;">Simple Additive Weighting (SAW)</b>
    </p>
</div>


<!-- STATISTIK RINGKAS -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:30px;">

    <!-- TOTAL DIVISI -->
    <div class="panel" style="padding:22px;display:flex;align-items:center;gap:16px;">
        <div style="background:#eef2ff;padding:14px;border-radius:12px;">
            <i class="fa fa-building" style="font-size:26px;color:#4338ca;"></i>
        </div>
        <div>
            <p style="font-size:13px;color:#6b7280;margin:0;">Total Divisi</p>
            <h2 style="font-size:22px;margin:0;color:#111827;font-weight:700;">
                <?= $jml_divisi ?>
            </h2>
        </div>
    </div>

    <!-- TOTAL KARYAWAN -->
    <div class="panel" style="padding:22px;display:flex;align-items:center;gap:16px;">
        <div style="background:#e0f2fe;padding:14px;border-radius:12px;">
            <i class="fa fa-users" style="font-size:26px;color:#0284c7;"></i>
        </div>
        <div>
            <p style="font-size:13px;color:#6b7280;margin:0;">Total Karyawan</p>
            <h2 style="font-size:22px;margin:0;color:#111827;font-weight:700;">
                <?= $jml_karyawan ?>
            </h2>
        </div>
    </div>

    <!-- TOTAL KRITERIA -->
    <div class="panel" style="padding:22px;display:flex;align-items:center;gap:16px;">
        <div style="background:#fef3c7;padding:14px;border-radius:12px;">
            <i class="fa fa-clipboard" style="font-size:26px;color:#d97706;"></i>
        </div>
        <div>
            <p style="font-size:13px;color:#6b7280;margin:0;">Total Kriteria</p>
            <h2 style="font-size:22px;margin:0;color:#111827;font-weight:700;">
                <?= $jml_kriteria ?>
            </h2>
        </div>
    </div>


    <!-- CARD TOTAL PENILAIAN -->
    <div class="panel" style="padding:22px;display:flex;align-items:center;gap:16px;">
        <div style="background:#ffe4e6;padding:14px;border-radius:12px;">
            <i class="fa fa-check-circle" style="font-size:26px;color:#e11d48;"></i>
        </div>
        <div>
            <p style="font-size:13px;color:#6b7280;margin:0;">Total Penilaian</p>
            <h2 style="font-size:22px;margin:0;color:#111827;font-weight:700;">
                <?= $jml_penilaian ?>
            </h2>
        </div>
    </div>



</div>

<!-- KARTU INFORMASI SAW -->
<div class="panel" 
     style="border-radius:22px;background:white;border:1px solid #E5E7EB;
            box-shadow:0 6px 18px rgba(0,0,0,0.05);overflow:hidden;margin-top:10px">

    <div style="padding:40px;text-align:center;">

        <h2 style="font-size:22px;color:#1d4ed8;margin-bottom:10px;font-weight:700;">
            ⚙️ Metode Perhitungan SAW
        </h2>

        <p style="font-size:15px;color:#475569;line-height:26px;">
            Sistem ini menggunakan metode <b>Simple Additive Weighting (SAW)</b>
            untuk menghitung nilai kinerja karyawan berdasarkan beberapa kriteria penilaian. 
            Hasil dari perhitungan SAW akan menghasilkan ranking terbaik dari seluruh karyawan
            yang telah dinilai.
        </p>

    </div>

    <div style="padding:0 0 30px 0;text-align:center;">
        <hr style="border:0;height:4px;border-radius:10px;width:75%;
                    margin:auto;background:#2563EB;">
    </div>

</div>
