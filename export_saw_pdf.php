<?php
session_start();
ob_start();

require './connect.php';
require './proses/proseshitung_saw.php';

// Import DOMPDF
require './class/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 5px; }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
        }
        th {
            background: #efefef;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Laporan Perangkingan Kinerja Karyawan<br>(Metode SAW)</h2>

<table>
    <thead>
        <tr>
            <th>Ranking</th>
            <th>Nama Karyawan</th>
            <th>Divisi</th>
            <th>Nilai Akhir</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $rank = 1;
        foreach ($hasil_ranking as $row) {
            echo "
            <tr>
                <td>$rank</td>
                <td>{$row['nama']}</td>
                <td>{$row['divisi']}</td>
                <td>" . number_format($row['hasil'], 4) . "</td>
            </tr>";
            $rank++;
        }
        ?>
    </tbody>
</table>

<br>
<p><i>Dicetak oleh: <?= $_SESSION['user']; ?><br>
Tanggal: <?= date("d-m-Y H:i"); ?></i></p>

</body>
</html>

<?php
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("hasil_perankingan_saw.pdf", ["Attachment" => false]);
?>
