<?php
session_start();
include 'koneksi.php';

// Pastikan yang login anggota
if (!isset($_SESSION['anggota'])) {
    die("Anda harus login terlebih dahulu.");
}

$msg = "";

// Ambil ID buku dari URL
if(isset($_GET['id'])){
    $id_buku = intval($_GET['id']); // amankan input
    $nama_anggota = $_SESSION['anggota'];
    $tanggal = date("Y-m-d");

    // Masukkan ke tabel peminjaman dengan status 'menunggu'
    $sql = "INSERT INTO peminjaman (nama_anggota, id_buku, tanggal_pinjam, status)
            VALUES ('$nama_anggota', '$id_buku', '$tanggal', 'menunggu')";

    if(mysqli_query($conn, $sql)){
        $msg = "<p style='color:green; font-weight:bold;'>Buku berhasil dipinjam, menunggu konfirmasi admin!</p>";
    } else {
        $msg = "<p style='color:red; font-weight:bold;'>Terjadi kesalahan: ".mysqli_error($conn)."</p>";
    }

} else {
    $msg = "<p style='color:red; font-weight:bold;'>ID buku tidak ditemukan.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Peminjaman Buku</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; }
        .container { width: 600px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 10px; }
        .msg { margin-bottom: 20px; text-align: center; }
        a { display:inline-block; margin-top:20px; padding:10px 15px; background:#3498db; color:white; text-decoration:none; border-radius:5px; }
    </style>
</head>
<body>
<div class="container">
    <h2>📚 Peminjaman Buku</h2>

    <div class="msg">
        <?php echo $msg; ?>
    </div>

    <a href="buku.php">⬅ Kembali ke Daftar Buku</a>
</div>
</body>
</html>