<?php
session_start();
$nama_user = isset($_SESSION['user']) ? $_SESSION['user'] : 'User';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard User</title>
    <style>
        body { 
            font-family: Arial; 
            background: #f0f2f5; 
            margin:0; 
            padding:0; 
        }

        .container { 
            width: 85%; 
            margin: 40px auto; 
            text-align: center; 
        }

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
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }

        .btn:hover {
            background: #2980b9;
            transform: translateY(-3px);
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
        <h2>Halo, <?= htmlspecialchars($nama_user); ?> 👋</h2>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <h2>📊 Dashboard Pengguna</h2>

    <div class="btn-container">

        <a href="pinjam.php" class="btn">
            📚 Peminjaman Buku
        </a>

        <a href="riwayat.php" class="btn orange">
            📜 Riwayat Pinjaman
        </a>

    </div>

</div>

</body>
</html>