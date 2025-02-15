<?php
include 'config.php';

// Jika pelanggan melakukan Check-out
if (isset($_GET['checkout_id'])) {
    $id = $_GET['checkout_id'];

    // Ambil kamar yang digunakan dalam reservasi
    $query = "SELECT kamar_id FROM reservasi WHERE reservasi_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $kamar_id = $row['kamar_id'];

    // Ubah status reservasi jadi "Selesai"
    $query = "UPDATE reservasi SET status = 'Selesai' WHERE reservasi_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Ubah status kamar jadi "Tersedia"
    $query = "UPDATE kamar SET status = 'Tersedia' WHERE kamar_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $kamar_id);
    $stmt->execute();

    header("Location: data_tamu.php");
    exit;
}

// Ambil daftar tamu yang sudah check-in dan belum check-out
$query = "SELECT r.reservasi_id, t.nama, k.nomor_kamar, r.tanggal_checkin, r.tanggal_checkout, r.status_pembayaran, r.status 
          FROM reservasi r 
          JOIN tamu t ON r.tamu_id = t.tamu_id
          JOIN kamar k ON r.kamar_id = k.kamar_id 
          WHERE r.status = 'Dipesan' AND r.status_pembayaran = 'Sudah Bayar'
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

<h3 style="text-align:center;">Daftar Tamu yang Sudah Check-in</h3>

<table border="1" width="100%">
    <thead>
        <tr style="background-color:blue; color:white;">
            <th>ID</th>
            <th>Nama Pelanggan</th>
            <th>No. Kamar</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status Pembayaran</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['reservasi_id']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['nomor_kamar']; ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal_checkin'])); ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal_checkout'])); ?></td>
                <td>
                    <?= ($row['status_pembayaran'] == 'Sudah Bayar') ? 
                        '<span style="color:green;">✅ Sudah Bayar</span>' : 
                        '<span style="color:red;">❌ Belum Dibayar</span>'; ?>
                </td>
                <td>
                    <a href="?checkout_id=<?= $row['reservasi_id']; ?>" style="color:red;">🏠 Check-out</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
