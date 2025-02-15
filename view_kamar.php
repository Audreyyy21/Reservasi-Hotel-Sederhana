<?php
// File: index.php
require 'config.php';

// Ambil semua data kamar
$sql    = "SELECT * FROM kamar";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Kamar</title>
    <!-- Panggil file CSS -->
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
           <a href="tambah_kamar.php">Tambah Kamar</a>
    </div>
       </div>
   </nav>

<!-- Container -->
<div class="container">
    <h3>Daftar Kamar</h3>
       </div>
   </nav>
    <!-- Tabel Kamar -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nomor Kamar</th>
                <th>Tipe Kamar</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['kamar_id']; ?></td>
                <td><?php echo $row['nomor_kamar']; ?></td>
                <td><?php echo $row['tipe']; ?></td>
                <td><?php echo $row['harga']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <!-- Contoh penggunaan class btn -->
                    <a class="btn" href="edit_kamar.php?kamar_id=<?php echo $row['kamar_id']; ?>">Edit</a>
                    <a class="btn btn-danger" 
                       href="hapus_kamar.php?kamar_id=<?php echo $row['kamar_id']; ?>" 
                       onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
