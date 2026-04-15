<?php
session_start();
include 'koneksi.php';

// Proteksi hanya admin
if(!isset($_SESSION['admin'])){
    header("Location: buku.php");
    exit;
}

if(isset($_POST['simpan'])){
    $judul    = $_POST['judul'];
    $penulis  = $_POST['penulis'];
    $penerbit = $_POST['penerbit']; // ambil penerbit dari form
    $tahun    = $_POST['tahun'];
    $stok     = $_POST['stok']; // ambil stok dari form

    // Pastikan kolom 'penerbit' & 'stok' ada di tabel buku
    $query = mysqli_query($conn, "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, stok) 
                                 VALUES ('$judul', '$penulis', '$penerbit', '$tahun', '$stok')");

    if($query){
        echo "<script>alert('Buku berhasil ditambahkan!'); window.location='dasboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan buku: ".mysqli_error($conn)."');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku</title>
    <style>
        body { font-family: Arial; background:#f4f4f4; }
        .box { width:400px; margin:80px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.2);}
        h2 { text-align:center; }
        input, button { width:100%; padding:10px; margin-top:10px; }
        button { background:#3498db; color:white; border:none; }
        button:hover { background:#2980b9; }
    </style>
</head>
<body>

<div class="box">
    <h2>Tambah Buku</h2>
    <form method="POST">
        <label>Judul Buku</label>
        <input type="text" name="judul" required>

        <label>Penulis</label>
        <input type="text" name="penulis" required>

        <label>Penerbit</label>
        <input type="text" name="penerbit" required>

        <label>Tahun Terbit</label>
        <input type="number" name="tahun" required>

        <label>Stok</label>
        <input type="number" name="stok" min="0" value="1" required>

        <button type="submit" name="simpan">Simpan</button>
    </form>
</div>

</body>
</html>