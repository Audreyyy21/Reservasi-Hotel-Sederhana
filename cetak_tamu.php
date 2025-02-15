<?php
// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Naikkan memory limit jika diperlukan
ini_set('memory_limit', '256M');

// Pastikan tidak ada output lain sebelum ini

// Muat library Dompdf dan koneksi database
require 'dompdf/autoload.inc.php';
require 'config.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Ambil bulan dan tahun dari URL, jika tidak ada gunakan bulan & tahun saat ini
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Siapkan query untuk mengambil data tamu berdasarkan bulan & tahun check-in
$query = "SELECT tamu.tamu_id, tamu.nama, tamu.email, tamu.telepon, tamu.alamat, tamu.kota, reservasi.tanggal_checkin 
          FROM tamu 
          JOIN reservasi ON tamu.tamu_id = reservasi.tamu_id
          WHERE MONTH(reservasi.tanggal_checkin) = ? 
          AND YEAR(reservasi.tanggal_checkin) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $bulan, $tahun);
$stmt->execute();
$result = $stmt->get_result();

// Buat tampilan HTML untuk PDF
$html = '<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Laporan Data Tamu</title>
  <style>
    /* Pengaturan margin halaman */
    @page {
      margin: 20px;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 0 auto;
      page-break-inside: auto; /* Supaya tabel bisa terpecah ke halaman selanjutnya */
      word-wrap: break-word;   /* Bungkus teks panjang */
    }
    tr {
      page-break-inside: avoid; /* Hindari pemotongan baris di tengah */
      page-break-after: auto;
    }
    table, th, td {
      border: 1px solid #000;
    }
    th, td {
      padding: 8px;
      text-align: center;
      vertical-align: top;
    }
    th {
      background-color: #007BFF;
      color: #fff;
    }
  </style>
</head>
<body>';

$html .= '<h2>Laporan Data Tamu - Bulan ' . $bulan . ' Tahun ' . $tahun . '</h2>';
$html .= '<table>
            <thead>
              <tr>
                <th>ID TAMU</th>
                <th>NAMA</th>
                <th>EMAIL</th>
                <th>TELEPON</th>
                <th>ALAMAT</th>
                <th>KOTA</th>
                <th>TANGGAL CHECK-IN</th>
              </tr>
            </thead>
            <tbody>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
                <td>' . $row['tamu_id'] . '</td>
                <td>' . $row['nama'] . '</td>
                <td>' . $row['email'] . '</td>
                <td>' . $row['telepon'] . '</td>
                <td>' . $row['alamat'] . '</td>
                <td>' . $row['kota'] . '</td>
                <td>' . date('d-m-Y', strtotime($row['tanggal_checkin'])) . '</td>
              </tr>';
}

$html .= '</tbody></table>';
$html .= '</body></html>';

// Inisialisasi Dompdf dengan opsi
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

// Muat HTML ke Dompdf
$dompdf->loadHtml($html);

// Set ukuran kertas dan orientasi (landscape atau portrait)
$dompdf->setPaper('A4', 'landscape');

// Render HTML menjadi PDF
$dompdf->render();

// Tampilkan PDF di browser (Attachment => 0 untuk menampilkan inline, 1 untuk download)
$dompdf->stream("Laporan_Tamu_{$bulan}-{$tahun}.pdf", ["Attachment" => 0]);
?>

