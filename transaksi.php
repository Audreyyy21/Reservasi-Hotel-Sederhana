<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config.php';

if (!isset($_GET['reservasi_id'])) {
    die("Reservasi tidak ditemukan.");
}

$reservasi_id = $_GET['reservasi_id'];
$tanggal_pembayaran = date('Y-m-d');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $metode_pembayaran = $_POST['metode_pembayaran'];

    // Ambil harga reservasi
    $query = "SELECT k.harga, k.markup_weekend, k.markup_hari_libur, r.tanggal_checkin 
              FROM reservasi r 
              JOIN kamar k ON r.kamar_id = k.kamar_id 
              WHERE r.reservasi_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $reservasi_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        die("Data reservasi tidak ditemukan.");
    }

    $harga = $row['harga'];
    $markup_weekend = $row['markup_weekend'];
    $markup_hari_libur = $row['markup_hari_libur'];
    $tanggal_checkin = $row['tanggal_checkin'];

    // Hitung harga final
    $dayOfWeek = date('N', strtotime($tanggal_checkin));
    $is_weekend = ($dayOfWeek == 6 || $dayOfWeek == 7);
    $is_libur = $conn->query("SELECT COUNT(*) AS count FROM hari_libur WHERE tanggal = '$tanggal_checkin'")->fetch_assoc()['count'] > 0;

    $harga_final = $is_libur ? $harga * $markup_hari_libur : ($is_weekend ? $harga * $markup_weekend : $harga);

    // Simpan transaksi
    $query = "INSERT INTO transaksi (reservasi_id, metode_pembayaran, jumlah, tanggal_pembayaran, status) 
              VALUES (?, ?, ?, ?, 'Lunas')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isds", $reservasi_id, $metode_pembayaran, $harga_final, $tanggal_pembayaran);
    $stmt->execute();

    // Update status reservasi & kamar
    $conn->query("UPDATE reservasi SET status = 'Selesai' WHERE reservasi_id = $reservasi_id");
    $conn->query("UPDATE kamar SET status = 'Tersedia' WHERE kamar_id = (SELECT kamar_id FROM reservasi WHERE reservasi_id = $reservasi_id)");

    echo "<p>Pembayaran sukses! Total yang dibayar: <strong>Rp " . number_format($harga_final, 0, ',', '.') . "</strong></p>";
    echo "<a href='index.php'>Kembali ke Beranda</a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayar Reservasi</title>
</head>
<body>
    <h2>Pilih Metode Pembayaran</h2>
    <form method="POST">
        <label for="metode_pembayaran">Metode Pembayaran:</label>
        <select name="metode_pembayaran" required>
            <option value="Transfer Bank">Transfer Bank</option>
            <option value="Tunai">Tunai</option>
        </select>
        <br><br>
        <button type="submit">Bayar Sekarang</button>
    </form>
</body>
</html>
