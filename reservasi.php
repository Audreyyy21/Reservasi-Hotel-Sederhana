<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $telepon = $_POST['telepon'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $kota = $_POST['kota'] ?? '';
    $tipe_kamar = $_POST['tipe_kamar'] ?? '';
    $tanggal_checkin = $_POST['tanggal_checkin'] ?? '';
    $tanggal_checkout = $_POST['tanggal_checkout'] ?? '';

    if (empty($tanggal_checkin) || empty($tanggal_checkout)) {
        die("⚠️ Tanggal check-in dan check-out wajib diisi.");
    }

    try {
        $date1 = new DateTime($tanggal_checkin);
        $date2 = new DateTime($tanggal_checkout);
    } catch (Exception $e) {
        die("⚠️ Format tanggal tidak valid.");
    }

    if ($date1 >= $date2) {
        die("⚠️ Tanggal check-out harus lebih dari check-in.");
    }

    $conn->begin_transaction();

    try {
        // Simpan tamu
        $query = "INSERT INTO tamu (nama, email, telepon, alamat, kota) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $nama, $email, $telepon, $alamat, $kota);
        $stmt->execute();
        $tamu_id = $conn->insert_id;

        // Pilih kamar tersedia berdasarkan tipe
        $query = "SELECT kamar_id, harga FROM kamar WHERE tipe = ? AND status = 'Tersedia' LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $tipe_kamar);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $kamar_id = $row['kamar_id'];
            $harga_per_malam = $row['harga'];
        } else {
            throw new Exception("⚠️ Maaf, tidak ada kamar tersedia untuk tipe ini.");
        }

        // Hitung total harga berdasarkan lama menginap
        $interval = $date1->diff($date2);
        $total_harga = $interval->days * $harga_per_malam;

        // Simpan reservasi
        $query = "INSERT INTO reservasi (tamu_id, kamar_id, tanggal_checkin, tanggal_checkout, status_pembayaran, status, total_harga) 
                  VALUES (?, ?, ?, ?, 'Belum Dibayar', 'Dipesan', ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iissi", $tamu_id, $kamar_id, $tanggal_checkin, $tanggal_checkout, $total_harga);
        $stmt->execute();
        $reservasi_id = $conn->insert_id;

        // Update status kamar
        $query = "UPDATE kamar SET status = 'Dipesan' WHERE kamar_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $kamar_id);
        $stmt->execute();

        // Commit transaksi
        $conn->commit();

        // Redirect ke halaman pembayaran
        header("Location: pembayaran.php?id_reservasi=$reservasi_id");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        die("⚠️ Terjadi kesalahan: " . $e->getMessage());
    }
}
?>