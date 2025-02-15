<?php
// File: tambah_kamar.php
require 'config.php';

if (isset($_POST['tambah'])) {
    $nomor_kamar = $_POST['nomor_kamar'];
    $tipe        = $_POST['tipe'];
    $harga       = $_POST['harga'];
    $status      = $_POST['status'];

    $sql_insert = "INSERT INTO kamar 
        (nomor_kamar, tipe, harga, status)
        VALUES 
        ('$nomor_kamar', '$tipe', '$harga', '$status')";

    if (mysqli_query($conn, $sql_insert)) {
        // Berhasil tambah
        echo "<script>alert('Data kamar berhasil ditambahkan.'); window.location='view_kamar.php';</script>";
    } else {
        // Gagal tambah
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kamar</title>
</head>
<body>
    <h1>Tambah Kamar</h1>
    <form method="POST" action="">
        <label>Nama Kamar:</label><br>
        <input type="text" name="tipe" required><br><br>
        <label>Nomor Kamar:</label><br>
        <input type="text" name="nomor_kamar" required><br><br>
        <label>Harga:</label><br>
        <input type="number" step="0.01" name="harga" required><br><br>
        <label>Status:</label><br>
        <select name="status">
            <option value="Tersedia">Tersedia</option>
            <option value="Dipesan">Dipesan</option>
        </select><br><br>

        <button type="submit" name="tambah">Simpan</button>
    </form>
    <br>
    <a href="view_kamar.php">Kembali ke Daftar Kamar</a>
</body>
</html>
