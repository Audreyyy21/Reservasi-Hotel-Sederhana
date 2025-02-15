<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_reservasi = $_POST['id_reservasi'] ?? '';
    $metode_pembayaran = $_POST['metode_pembayaran'] ?? '';

    if (empty($id_reservasi) || empty($metode_pembayaran)) {
        die("⚠️ Harap pilih metode pembayaran.");
    }

    // Periksa apakah reservasi valid
    $cek_query = "SELECT * FROM reservasi WHERE reservasi_id = ?";
    $stmt = $conn->prepare($cek_query);
    $stmt->bind_param("i", $id_reservasi);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("⚠️ Reservasi tidak ditemukan.");
    }

    // Perbaiki query UPDATE dengan koma yang benar antara kolom yang akan diupdate
    $query = "UPDATE reservasi SET metode_pembayaran = ?, status_pembayaran = 'Sudah Bayar' WHERE reservasi_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $metode_pembayaran, $id_reservasi);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "<h2>✅ Pembayaran Berhasil!</h2>";
        echo "<p>Metode: <b>$metode_pembayaran</b></p>";
        echo "<p>Reservasi Anda telah dikonfirmasi.</p>";
    
        // Redirect otomatis ke daftar reservasi setelah 2 detik
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'reservasi_list.php';
                }, 2000);
              </script>";
    } else {
        // Jika tidak ada baris yang terpengaruh, tampilkan error statement jika ada
        $error = $stmt->error;
        echo "⚠️ Gagal memperbarui status pembayaran. " . ($error ? "Error: $error" : "Tidak ada perubahan data.");
    }   
}
?>
