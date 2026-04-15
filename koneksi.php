<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "perpus";

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Optional: set charset biar aman
mysqli_set_charset($conn, "utf8");
?>