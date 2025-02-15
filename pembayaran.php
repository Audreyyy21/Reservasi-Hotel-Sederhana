<?php
include 'config.php';

// Ambil ID reservasi dari parameter
$id_reservasi = $_GET['id_reservasi'] ?? null;

if (!$id_reservasi) {
    die("⚠️ ID reservasi tidak ditemukan.");
}

// Ambil data reservasi
$query = "SELECT r.total_harga, t.nama, k.nomor_kamar 
          FROM reservasi r 
          JOIN tamu t ON r.tamu_id = t.tamu_id
          JOIN kamar k ON r.kamar_id = k.kamar_id
          WHERE r.reservasi_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_reservasi);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("⚠️ Data reservasi tidak ditemukan.");
}

$total_harga = number_format($data['total_harga'], 0, ',', '.');

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Reservasi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2 style="text-align:center;">Pembayaran Reservasi</h2>

<p><strong>Nama:</strong> <?= htmlspecialchars($data['nama']); ?></p>
<p><strong>No. Kamar:</strong> <?= htmlspecialchars($data['nomor_kamar']); ?></p>
<p><strong>Total Harga:</strong> Rp <?= $total_harga; ?></p>

<form method="POST" action="proses_pembayaran.php">
    <input type="hidden" name="id_reservasi" value="<?= $id_reservasi; ?>">
    
    <label for="metode_pembayaran">Metode Pembayaran:</label>
    <select id="metode_pembayaran" name="metode_pembayaran" required onchange="tampilkanRekening()">
        <option value="">-- Pilih --</option>
        <option value="Transfer Bank">Transfer Bank</option>
        <option value="Tunai">Tunai</option>
    </select>

    <div id="info_rekening" style="display: none;">
        <p><strong>Silakan transfer ke:</strong></p>
        <p>🏦 <strong>BCA:</strong> 123-456-7890 a.n. Hotel Sederhana</p>
        <p>🏦 <strong>Mandiri:</strong> 098-765-4321 a.n. Hotel Sederhana</p>
    </div>

    <br>
    <button type="submit" style="background-color: green; color: white; padding: 10px; border: none;">✅ Konfirmasi Pembayaran</button>
</form>

<script>
function tampilkanRekening() {
    var metode = document.getElementById("metode_pembayaran").value;
    document.getElementById("info_rekening").style.display = (metode === "Transfer Bank") ? "block" : "none";
}
</script>

</body>
</html>
