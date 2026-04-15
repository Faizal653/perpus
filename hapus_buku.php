<?php
session_start();
include 'koneksi.php';

// Proteksi: hanya admin yang bisa menghapus
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Anda tidak memiliki akses!'); window.location='daftar_buku.php';</script>";
    exit;
}

// Pastikan ada ID buku
if (isset($_GET['id'])) {
    $id_buku = intval($_GET['id']); // amankan input

    // Hapus buku dari database
    $hapus = mysqli_query($conn, "DELETE FROM buku WHERE id_buku='$id_buku'");

    if ($hapus) {
        echo "<script>alert('Buku berhasil dihapus!'); window.location='daftar_buku.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus buku: ".mysqli_error($conn)."'); window.location='daftar_buku.php';</script>";
    }
} else {
    echo "<script>alert('ID buku tidak ditemukan!'); window.location='buku.php';</script>";
}
?>