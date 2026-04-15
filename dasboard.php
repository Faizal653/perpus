<?php
session_start();
include 'koneksi.php';

// Proteksi admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Hitung data
$jml_buku       = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM buku"))['total'];
$jml_anggota    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM anggota"))['total'];
$jml_transaksi  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi"))['total'];
$jml_peminjaman = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman"))['total'];

$nama_admin = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; margin:0; padding:0; }
        .container { width: 85%; margin: 40px auto; text-align: center; }

        .header {
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:30px;
        }

        h2 { color: #333; }

        .logout {
            padding: 8px 18px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout:hover { background: #c0392b; }

        .btn-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 25px 40px;
            margin: 15px;
            font-size: 18px;
            text-decoration: none;
            border-radius: 12px;
            transition: 0.3s;
            position: relative;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }

        .btn:hover {
            background: #2980b9;
            transform: translateY(-3px);
        }

        .badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            padding: 5px 10px;
            border-radius: 50%;
            font-size: 14px;
        }

        .green { background: #2ecc71; }
        .green:hover { background: #27ae60; }

        .orange { background: #f39c12; }
        .orange:hover { background: #d68910; }

    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h2>Halo, <?= htmlspecialchars($nama_admin); ?> 👋</h2>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <h2>📊 Dashboard Perpustakaan</h2>

    <div class="btn-container">

        <a href="buku.php" class="btn">
            📚 Data Buku
            <span class="badge"><?= $jml_buku ?></span>
        </a>

        <a href="tambah_user.php" class="btn">
            👤 Data Anggota
            <span class="badge"><?= $jml_anggota ?></span>
        </a>

        <a href="transaksi.php" class="btn">
            💳 Data Transaksi
            <span class="badge"><?= $jml_transaksi ?></span>
        </a>

        <a href="konfirmasi.php" class="btn orange">
            ✅ Konfirmasi Peminjaman
            <span class="badge"><?= $jml_peminjaman ?></span>
        </a>

        <a href="tambah_buku.php" class="btn green">
            ➕ Tambah Buku
        </a>

    </div>

</div>

</body>
</html>