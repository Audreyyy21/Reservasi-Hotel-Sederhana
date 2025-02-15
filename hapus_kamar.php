
<?php
// File: hapus_kamar.php
require 'config.php';

// Debug: cek isi $_GET
echo '<pre>';
print_r($_GET);
echo '</pre>';

// Pastikan ada parameter id
if (!isset($_GET['kamar_id'])) {
    echo "ID kamar tidak ditemukan!";
    exit;
}

$kamar_id = $_GET['kamar_id'];

$sql_delete = "DELETE FROM kamar WHERE kamar_id = '$kamar_id'";
if (mysqli_query($conn, $sql_delete)) {
    echo "<script>alert('Data kamar berhasil dihapus.'); window.location='view_kamar.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
