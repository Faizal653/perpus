<?php
include 'koneksi.php';

// ✅ PROSES KEMBALIKAN
if (isset($_GET['kembalikan'])) {
    $id = mysqli_real_escape_string($conn, $_GET['kembalikan']);

    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM peminjaman WHERE id='$id'"));
    $id_buku = $data['id_buku'];
    

    mysqli_query($conn, "
        UPDATE peminjaman 
SET status='Menunggu Konfirmasi Kembali' 
WHERE id='$id'
    ");

    mysqli_query($conn, "
        UPDATE buku 
        SET stok = stok + 1 
        WHERE id_buku='$id_buku'
    ");

    // ✅ NOTIFIKASI
    header("Location: riwayat.php?pesan=sukses");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Peminjaman</title>
    <style>
        body { font-family: Arial; background:#f0f2f5; }
        .container { width: 90%; margin: 30px auto; }
        h2 { text-align:center; }
        table { width:100%; border-collapse: collapse; background:#fff; }
        th, td { padding:10px; border-bottom:1px solid #ddd; text-align:center; }
        th { background:#3498db; color:white; }
        .back { display:inline-block; margin-bottom:15px; background:#555; color:white; padding:8px 12px; text-decoration:none; border-radius:5px; }
    </style>
</head>

<body>

<!-- ✅ TOMBOL KEMBALI -->
<a href="dashboarduser.php" class="back">⬅ Kembali ke Dashboard</a>

<!-- ✅ NOTIFIKASI -->
<?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'menunggu'): ?>
    <p style="color:orange; text-align:center; font-weight:bold;">
        ⏳ Menunggu konfirmasi admin
    </p>
<?php endif; ?>

<h2>Riwayat Peminjaman</h2>

<table border="1">
<tr>
    <th>Judul Buku</th>
    <th>Nama Anggota</th>
    <th>Tanggal Pinjam</th>
    <th>Tanggal Kembali</th>
    <th>Status</th>
</tr>

<?php

$query = mysqli_query($conn, "
SELECT 
    buku.judul,
    peminjaman.id,
    peminjaman.nama_anggota,
    peminjaman.tanggal_pinjam,
    peminjaman.tanggal_kembali,
    peminjaman.status
FROM peminjaman
LEFT JOIN buku ON peminjaman.id_buku = buku.id_buku
ORDER BY peminjaman.id DESC
");

while ($data = mysqli_fetch_assoc($query)) {
?>
<tr>
    <td><?= $data['judul'] ?? '-'; ?></td> 
    <td><?= $data['nama_anggota'] ?? '-'; ?></td> 
    <td><?= $data['tanggal_pinjam']; ?></td> 
    <td><?= $data['tanggal_kembali'] ? $data['tanggal_kembali'] : '-'; ?></td> 
    <td><?= $data['status']; ?></td> 
    <td>
        <?php if ($data['status'] == 'Dipinjam' || $data['status'] == 'Disetujui'): ?>
            <a href="?kembalikan=<?= $data['id']; ?>" 
               onclick="return confirm('Kembalikan buku ini?')">
               🔄 Kembalikan
            </a>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>