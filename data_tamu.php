<?php
include 'config.php';

// Query untuk mengambil data tamu
$query = "SELECT tamu_id, nama, email, telepon, alamat, kota FROM tamu";
$result = $conn->query($query);

// Periksa apakah query berhasil
if (!$result) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Tamu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
        }
        th {
            background-color: blue;
            color: white;
        }
        .back-button {
            margin-top: 20px;
        }
        .back-button a {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
        }
        .back-button a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Data Tamu</h2>
    <table>
        <thead>
            <tr>
                <th>ID TAMU</th>
                <th>NAMA</th>
                <th>EMAIL</th>
                <th>TELEPON</th>
                <th>ALAMAT</th>
                <th>KOTA</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['tamu_id']; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['telepon']; ?></td>
                    <td><?= $row['alamat']; ?></td>
                    <td><?= $row['kota']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <form action="cetak_tamu.php" method="GET" style="margin: 20px; text-align: center;">
    <label for="bulan">Pilih Bulan:</label>
    <select name="bulan" required>
        <?php for ($m = 1; $m <= 12; $m++) {
            $month = str_pad($m, 2, '0', STR_PAD_LEFT);
            echo "<option value='$month'>$month</option>";
        } ?>
    </select>

    <label for="tahun">Pilih Tahun:</label>
    <select name="tahun" required>
        <?php
        $yearNow = date('Y');
        for ($y = $yearNow; $y >= ($yearNow - 5); $y--) {
            echo "<option value='$y'>$y</option>";
        }
        ?>
    </select>

    <button type="submit">📄 Cetak PDF</button>
</form>


    <!-- Tombol Kembali ke Home -->
    <div class="back-button">
        <a href="index.php">🏠 Back to Home</a>
    </div>

</body>
</html>
