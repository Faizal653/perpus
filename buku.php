<?php
include 'koneksi.php';

// ================= PINJAM =================
if (isset($_GET['pinjam'])) {
    $id_buku = intval($_GET['pinjam']);

    $cek = mysqli_query($conn, "SELECT stok FROM buku WHERE id_buku='$id_buku'");
    $data = mysqli_fetch_assoc($cek);

    if ($data && $data['stok'] > 0) {

        mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id_buku='$id_buku'");

        mysqli_query($conn, "
            INSERT INTO transaksi (id_buku, status)
            VALUES ('$id_buku', 'dipinjam')
        ");

        echo "<script>alert('Buku dipinjam!'); window.location='kelola.php';</script>";
        exit;

    } else {
        echo "<script>alert('Stok habis!'); window.location='kelola.php';</script>";
        exit;
    }
}

// ================= KEMBALIKAN =================
if (isset($_GET['kembali'])) {
    $id = intval($_GET['kembali']);

    $cek = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi='$id'");
    $trx = mysqli_fetch_assoc($cek);

    if ($trx && $trx['status'] != 'dikembalikan') {

        $id_buku = $trx['id_buku'];

        mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id_buku='$id_buku'");
        mysqli_query($conn, "UPDATE transaksi SET status='dikembalikan' WHERE id_transaksi='$id'");

        echo "<script>alert('Buku dikembalikan!'); window.location='kelola.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Buku</title>

    <style>
    body {
        font-family: Arial;
        background: #f4f6f9;
        padding: 20px;
    }

    table {
        width: 80%;
        margin: auto;
        border-collapse: collapse;
        background: white;
    }

    th, td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
    }

    th {
        background: #00ccff;
        color: white;
    }

    button {
        padding: 5px 10px;
        border: none;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }

    .pinjam { background: #007bff; }
    .kembali { background: #28a745; }
    .disabled { background: gray; cursor: not-allowed; }
    </style>
</head>

<body>

<h2 align="center">📚 Kelola Buku</h2>

<table border="1" cellpadding="10" cellspacing="0" align="center">
    <tr>
        <th>No</th>
        <th>Judul</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>

<?php
$query = mysqli_query($conn, "SELECT * FROM buku");
$no = 1;

while ($data = mysqli_fetch_assoc($query)) {
?>

<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $data['judul']; ?></td>
    <td><?php echo $data['stok']; ?></td>
    <td>
        <a href="?hapus=<?php echo $data['id_buku']; ?>"
           onclick="return confirm('Yakin ingin menghapus buku ini?')">
            <button style="background:red; color:white;">Hapus</button>
        </a>
    </td>
</tr>

<?php } ?>
</table>

</body>
</html>