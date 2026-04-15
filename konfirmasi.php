<?php
session_start();
include 'koneksi.php';

// Proteksi admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Proses ACC / Tolak / Hapus
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id   = mysqli_real_escape_string($conn, $_GET['id']);
    $aksi = $_GET['aksi'];

    if ($aksi == "setuju") {
        mysqli_query($conn, "UPDATE peminjaman SET status='Disetujui' WHERE id='$id'");
        $p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM peminjaman WHERE id='$id'"));
        $id_buku = $p['id_buku'];
        $tanggal_pinjam = $p['tanggal_pinjam'];
        mysqli_query($conn, "INSERT INTO transaksi (id_buku, tanggal_pinjam, tanggal_kembali, status)
                             VALUES ('$id_buku','$tanggal_pinjam', NULL,'Dipinjam')");
    } elseif ($aksi == "tolak") {
        mysqli_query($conn, "UPDATE peminjaman SET status='Ditolak' WHERE id='$id'");
    } elseif ($aksi == "hapus") {
        mysqli_query($conn, "DELETE FROM peminjaman WHERE id='$id'");
    }

    header("Location: konfirmasi.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Peminjaman</title>
    <style>
        body { font-family: Arial; background:#f0f2f5; }
        .container { width: 90%; margin: 30px auto; }
        h2 { text-align:center; }
        table { width:100%; border-collapse: collapse; background:#fff; }
        th, td { padding:10px; border-bottom:1px solid #ddd; text-align:center; }
        th { background:#3498db; color:white; }
        .btn { padding:5px 12px; text-decoration:none; color:white; border-radius:5px; margin:2px; display: inline-block; }
        .setuju { background:#2ecc71; }
        .tolak { background:#e74c3c; }
        .hapus { background:#e67e22; }
        .disabled { background: #bdc3c7; cursor: not-allowed; color: #7f8c8d; }
        .badge { padding:3px 8px; border-radius:5px; font-weight:bold; color:white; font-size: 12px; }
        .pending { background:orange; }
        .disetujui { background:green; }
        .ditolak { background:red; }
        .back { display:inline-block; margin-bottom:15px; background:#555; color:white; padding:8px 12px; text-decoration:none; border-radius:5px; }
    </style>
</head>
<body>

<div class="container">
    <a href="dasboard.php" class="back">⬅ Kembali ke Dasboard</a>
    <h2>📚 Konfirmasi Peminjaman Buku</h2>

    <table>
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php
        $q = mysqli_query($conn, "SELECT  judul 
                                  FROM peminjaman  
                                  JOIN buku  ON id_buku = id_buku
                                  ORDER BY id DESC");

        $no = 1;
        while($row = mysqli_fetch_assoc($q)){
            $status_db = strtolower($row['status']); 

            $badge_class = "pending";
            if ($status_db == "disetujui") $badge_class = "disetujui";
            elseif ($status_db == "ditolak") $badge_class = "ditolak";

            echo "<tr>
                <td>$no</td>
                <td>".htmlspecialchars($row['judul'])."</td>
                <td>{$row['tanggal_pinjam']}</td>
                <td><span class='badge $badge_class'>{$row['status']}</span></td>
                <td>";

            if ($status_db == "pending" || $status_db == "menunggu") {
                echo "
                <a class='btn setuju' href='?aksi=setuju&id={$row['id']}' onclick=\"return confirm('Setujui peminjaman ini?')\">✔ Setujui</a>
                <a class='btn tolak' href='?aksi=tolak&id={$row['id']}' onclick=\"return confirm('Tolak peminjaman ini?')\">❌ Tolak</a>
                ";
            } else {
                echo "
                <span class='btn disabled'>✔ Setujui</span>
                <span class='btn disabled'>❌ Tolak</span>
                ";
            }

            echo "<a class='btn hapus' href='?aksi=hapus&id={$row['id']}' onclick=\"return confirm('Hapus data peminjaman ini?')\">🗑 Hapus</a>";

            echo "</td></tr>";
            $no++;
        }
        ?>
    </table>
</div>

</body>
</html>