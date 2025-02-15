<?php
require 'config.php';

if (isset($_GET['tipe'])) {
    $tipe = $_GET['tipe'];
    $query = "SELECT COUNT(*) as jumlah FROM kamar WHERE tipe = ? AND status = 'Tersedia'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $tipe);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo $row['jumlah'];
}
?>
