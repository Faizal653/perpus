<?php
include 'koneksi.php';

// ================= PINJAM BUKU =================
if (isset($_GET['pinjam'])) {
    $id_buku = intval($_GET['pinjam']);

    $cek = mysqli_query($conn, "SELECT stok FROM buku WHERE id_buku='$id_buku'");
    $data = mysqli_fetch_assoc($cek);

    if ($data && $data['stok'] > 0) {

        // stok -1
        mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id_buku='$id_buku'");

        // insert transaksi
        mysqli_query($conn, "
            INSERT INTO transaksi (id_buku, status)
            VALUES ('$id_buku', 'dipinjam')
        ");

        echo "<script>
                alert('Buku berhasil dipinjam!');
                window.location='pinjam.php';
              </script>";
        exit;

    } else {
        echo "<script>
                alert('Stok habis!');
                window.location='pinjam.php';
              </script>";
        exit;
    }
}


// ================= KEMBALIKAN BUKU =================
if (isset($_GET['kembali'])) {
    $id = intval($_GET['kembali']);

    $cek = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi='$id'");
    $data = mysqli_fetch_assoc($cek);

    if ($data) {

        if ($data['status'] == 'dikembalikan') {
            echo "<script>
                    alert('Buku sudah dikembalikan!');
                    window.location='pinjam.php';
                  </script>";
            exit;
        }

        $id_buku = $data['id_buku'];

        // stok +1
        mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id_buku='$id_buku'");

        // update status
        mysqli_query($conn, "UPDATE transaksi SET status='dikembalikan' WHERE id='$id'");

        echo "<script>
                alert('Buku berhasil dikembalikan!');
                window.location='pinjam.php';
              </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Buku</title>

    <!-- AUTO REFRESH -->
    <meta http-equiv="refresh" content="5">

    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f2f5;
        margin: 0;
        padding: 20px;
        color: #333;
    }

    .container { width: 90%; margin: auto; }

    .card {
        width: 220px;
        background: #fff;
        padding: 15px;
        margin: 15px;
        float: left;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        text-align: center;
    }

    button {
        margin-top: 8px;
        padding: 6px 0;
        width: 100px;
        border: none;
        border-radius: 6px;
        color: white;
        cursor: pointer;
    }

    .pinjam { background: #00ccff; }
    .kembali { background: #28a745; }
    .stok-habis { background: gray; }

    .clear { clear: both; }
    </style>
</head>

<body>

<div class="container">
    <h2>📚 Daftar Buku</h2>
    <hr>

<?php
$query = mysqli_query($conn, "SELECT * FROM buku");

while ($data = mysqli_fetch_assoc($query)) {

    $id_buku = $data['id_buku'];
    $judul = $data['judul'];
    $stok = $data['stok'];
?>

    <div class="card">
        <h4><?php echo $judul; ?></h4>
        <p>Stok: <?php echo $stok; ?></p>

        <!-- PINJAM -->
        <?php if ($stok > 0): ?>
            <a href="?pinjam=<?php echo $id_buku; ?>">
                <button class="pinjam">Pinjam</button>
            </a>
        <?php else: ?>
            <button class="stok-habis" disabled>Stok Habis</button>
        <?php endif; ?>

        <!-- KEMBALIKAN -->
        <a href="?kembali=<?php echo $id_buku; ?>"
           onclick="return confirm('Yakin ingin mengembalikan buku ini?')">
            <button class="kembali">Kembalikan</button>
        </a>

    </div>

<?php } ?>

<div class="clear"></div>
</div>

</body>
</html>