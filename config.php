<?php
$host = "localhost";  // Bisa juga pakai "127.0.0.1" jika masih error
$user = "root";  // Username default MySQL
$pass = "";  // Biarkan kosong jika tidak ada password
$db = "reservasi_hotel_sederhana";  // Pastikan nama database sesuai

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
