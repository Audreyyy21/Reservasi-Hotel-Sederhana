<?php
// File: edit_kamar.php
require 'config.php';

// Pastikan ada parameter id
if (!isset($_GET['kamar_id'])) {
    echo "ID kamar tidak ditemukan!";
    exit;
}

$kamar_id = $_GET['kamar_id'];

// Ambil data kamar berdasarkan id
$sql_kamar  = "SELECT * FROM kamar WHERE kamar_id = '$kamar_id'";
$res_kamar  = mysqli_query($conn, $sql_kamar);
$data_kamar = mysqli_fetch_assoc($res_kamar);

if (!$data_kamar) {
    echo "Data kamar tidak ditemukan!";
    exit;
}

// Jika tombol update ditekan
if (isset($_POST['update'])) {
    $tipe     = $_POST['tipe'];
    $harga  = $_POST['harga'];
    $status         = $_POST['status'];

    $sql_update = "UPDATE kamar SET 
        tipe='$tipe',
        harga='$harga',
        status='$status'
        WHERE kamar_id='$kamar_id'
    ";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Data kamar berhasil diubah.'); window.location='view_kamar.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Kamar</title>
</head>
<body>
    <h1>Edit Kamar</h1>
    <form method="POST" action="">
        <label>Nama Kamar:</label><br>
        <input type="text" name="tipe" value="<?php echo $data_kamar['tipe']; ?>" required><br><br>
        <label>Harga:</label><br>
        <input type="number" step="0.01" name="harga" value="<?php echo $data_kamar['harga']; ?>" required><br><br>
        <label>Status:</label><br>
        <select name="status">
            <option value="Tersedia" <?php if($data_kamar['status'] == "Tersedia") echo "selected"; ?>>Tersedia</option>
            <option value="Dipesan" <?php if($data_kamar['status'] == "Dipesan") echo "selected"; ?>>Dipesan</option>
        </select><br><br>

        <button type="submit" name="update">Update</button>
    </form>
    <br>
    <a href="view_kamar.php">Kembali ke Daftar Kamar</a>
</body>
</html>
