<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Anggota</title>
<style>
body { font-family: Arial, sans-serif; background:#f0f2f5; margin:0; padding:0; }
.container { width:90%; margin:20px auto; }
.header { display:flex; justify-content:space-between; align-items:center; background:#2ecc71; color:#fff; padding:10px 20px; border-radius:5px; }
.table-container { background:#fff; padding:20px; border-radius:5px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
table { width:100%; border-collapse:collapse; margin-top:10px; }
th, td { padding:8px; text-align:left; border-bottom:1px solid #ddd; }
th { background:#2ecc71; color:#fff; }
</style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Data Anggota 📋</h2>
    </div>

    <div class="table-container">
        <h3>Daftar Anggota</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Alamat</th>
                <th>No. Telepon</th>
            </tr>
            <?php
            $q = mysqli_query($conn, "SELECT nama_lengkap, alamat, nomor_telepon FROM anggota ORDER BY nama_lengkap ASC");
            $no = 1;
            while($row = mysqli_fetch_assoc($q)){
                echo "<tr>
                        <td>$no</td>
                        <td>".htmlspecialchars($row['nama_lengkap'])."</td>
                        <td>".htmlspecialchars($row['alamat'])."</td>
                        <td>".htmlspecialchars($row['nomor_telepon'])."</td>
                    </tr>";
                $no++;
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>