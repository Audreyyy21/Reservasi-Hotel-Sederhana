<?php
include 'config.php';

// Jika ada konfirmasi pembayaran
if (isset($_GET['bayar_id'])) {
    $id = $_GET['bayar_id'];
    $query = "UPDATE reservasi SET status_pembayaran = 'Sudah Bayar' WHERE reservasi_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Redirect kembali ke halaman data reservasi setelah konfirmasi
    header("Location: data_reservasi.php");
    exit;
}

// Jika ada konfirmasi check-out
if (isset($_GET['checkout_id'])) {
    $id = $_GET['checkout_id'];

    // Ubah status reservasi menjadi selesai
    $query = "UPDATE reservasi SET status = 'Selesai' WHERE reservasi_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Mengubah status kamar menjadi tersedia kembali
    $query = "UPDATE kamar SET status = 'Tersedia' WHERE kamar_id = (SELECT kamar_id FROM reservasi WHERE reservasi_id = ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: data_reservasi.php");
    exit;
}

// Ambil data reservasi
$query = "SELECT r.reservasi_id, t.nama, k.nomor_kamar, r.tanggal_checkin, r.tanggal_checkout, 
                 r.status_pembayaran, r.status, k.harga
          FROM reservasi r 
          JOIN tamu t ON r.tamu_id = t.tamu_id
          JOIN kamar k ON r.kamar_id = k.kamar_id 
          ORDER BY r.tanggal_checkin ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Reservasi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
       <h2 class="logo">Reservasi Hotel Sederhana ABC</h2>
       <div class="nav-links">
           <a href="index.php">Reservasi</a>
           <a href="data_tamu.php">Data Tamu</a>
           <a href="reservasi_list.php">Data Reservasi</a>
           <a href="view_kamar.php">Data Kamar</a>
       </div>
   </nav>

<h3 style="text-align:center;">Daftar Reservasi</h3>

<table border="1" width="100%">
    <thead>
        <tr style="background-color:blue; color:white;">
            <th>ID Reservasi</th>
            <th>Nama Tamu</th>
            <th>No. Kamar</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Total Harga</th>
            <th>Status Pembayaran</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { 
            // Hitung total harga berdasarkan lama menginap
            $checkin = new DateTime($row['tanggal_checkin']);
            $checkout = new DateTime($row['tanggal_checkout']);
            $lama_menginap = $checkout->diff($checkin)->days;
            $total_harga = $lama_menginap * $row['harga'];
        ?>
            <tr>
                <td><?= $row['reservasi_id']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['nomor_kamar']; ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal_checkin'])); ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal_checkout'])); ?></td>
                <td>Rp <?= number_format($total_harga, 0, ',', '.'); ?></td>
                <td>
                    <?php if ($row['status_pembayaran'] == 'Sudah Bayar') { ?>
                        <span style="color:green; font-weight:bold;">✅ Sudah Bayar</span>
                    <?php } else { ?>
                        <span style="color:red; font-weight:bold;">❌ Belum Dibayar</span>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($row['status'] == 'Selesai') { ?>
                        ✅ Selesai
                    <?php } else { ?>
                        <span style="color:orange; font-weight:bold;"><?= $row['status']; ?></span>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($row['status_pembayaran'] == 'Belum Dibayar') { ?>
                        <a href="?bayar_id=<?= $row['reservasi_id']; ?>" style="color:blue;">💰 Konfirmasi Bayar</a>
                    <?php } elseif ($row['status'] != 'Selesai') { ?>
                        <a href="?checkout_id=<?= $row['reservasi_id']; ?>" style="color:red;">🏠 Check-out</a>
                    <?php } else { ?>
                        ✅ Selesai
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
