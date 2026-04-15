<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['id_user'])) {
    echo "User belum login!";
    exit;
}

$id_user = $_SESSION['id_user'];
?>

<h2>Riwayat Peminjaman</h2>

<table border="1">
<tr>
    <th>Judul Buku</th>
    <th>Tanggal Pinjam</th>
    <th>Tanggal Kembali</th>
    <th>Status</th>
</tr>

<?php
$query = mysqli_query($conn, "
SELECT buku.judul, peminjaman.*
FROM peminjaman
JOIN buku ON buku.id = peminjaman.id_buku
WHERE peminjaman.id_user = '$id_user'
");

while ($data = mysqli_fetch_array($query)) {
?>
<tr>
    <td><?= $data['judul']; ?></td>
    <td><?= $data['tanggal_pinjam']; ?></td>
    <td><?= $data['tanggal_kembali']; ?></td>
    <td><?= $data['status']; ?></td>
</tr>
<?php } ?>

</table>