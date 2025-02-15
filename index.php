<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Hotel</title>
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

   <h3>Masukkan Data Diri Anda</h3>
   <form method="POST" action="reservasi.php">
       <label for="nama">Nama:</label>
       <input type="text" name="nama" required><br><br>

       <label for="email">Email:</label>
       <input type="email" name="email" required><br><br>

       <label for="telepon">No. Telepon:</label>
       <input type="text" name="telepon" required><br><br>

       <label for="alamat">Alamat:</label>
       <input type="text" name="alamat"><br><br>

       <label for="kota">Kota:</label>
       <input type="text" name="kota"><br><br>

       <label for="tipe_kamar">Pilih Tipe Kamar:</label>
       <select name="tipe_kamar" id="tipe_kamar" required>
           <option value="">-- Pilih Tipe Kamar --</option>
           <?php
           $query = "SELECT DISTINCT tipe FROM kamar WHERE status = 'Tersedia'";
           $result = $conn->query($query);
           while ($row = $result->fetch_assoc()) {
               echo "<option value='{$row['tipe']}'>{$row['tipe']}</option>";
           }
           ?>
       </select><br>
       <p id="kamarTersedia" style="color: green; font-weight: bold;"></p><br>

       <label for="tanggal_checkin">Tanggal Check-in:</label>
       <input type="date" id="tanggal_checkin" name="tanggal_checkin" required><br><br>

       <label for="tanggal_checkout">Tanggal Check-out:</label>
       <input type="date" id="tanggal_checkout" name="tanggal_checkout" required><br><br>

       <button type="submit">Lanjutkan Reservasi</button>
   </form>

   <script>
       document.addEventListener("DOMContentLoaded", function () {
           let today = new Date().toISOString().split("T")[0];
           let checkinInput = document.getElementById("tanggal_checkin");
           let checkoutInput = document.getElementById("tanggal_checkout");
           let tipeKamar = document.getElementById("tipe_kamar");

           // Set minimal tanggal check-in ke hari ini
           checkinInput.setAttribute("min", today);
           checkoutInput.setAttribute("min", today);

           checkinInput.addEventListener("change", function () {
               checkoutInput.setAttribute("min", this.value);
           });

           checkoutInput.addEventListener("change", function () {
               if (this.value <= checkinInput.value) {
                   alert("Tanggal check-out harus lebih dari check-in!");
                   this.value = "";
               }
           });

           // Cek jumlah kamar tersedia saat memilih tipe kamar
           tipeKamar.addEventListener("change", function () {
               let tipe = this.value;
               if (tipe !== "") {
                   fetch(`cek_kamar.php?tipe=${tipe}`)
                       .then(response => response.text())
                       .then(data => {
                           document.getElementById("kamarTersedia").innerHTML = "Kamar tersedia: " + data;
                       })
                       .catch(error => console.error("Error:", error));
               } else {
                   document.getElementById("kamarTersedia").innerHTML = "";
               }
           });
       });
   </script>

</body>
</html>
