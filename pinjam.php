<?php
session_start();
include 'koneksi.php';

// ================= PROSES PINJAM =================
if (isset($_POST['pinjam'])) {
    $id_buku = intval($_POST['id_buku']);
    $tgl_pinjam = $_POST['tanggal_pinjam'];
    $tgl_kembali = $_POST['tanggal_kembali'];

    // validasi tanggal
    if ($tgl_kembali < $tgl_pinjam) {
        echo "<script>alert('Tanggal kembali harus setelah tanggal pinjam!');</script>";
    } else {

        // cek buku benar ada atau tidak
        $cek = mysqli_query($conn, "SELECT stok FROM buku WHERE id_buku='$id_buku' LIMIT 1");

        if (!$cek) {
            die("QUERY ERROR: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($cek) == 0) {
            die("Buku tidak ditemukan!");
        }

        $data = mysqli_fetch_assoc($cek);

        if ($data['stok'] > 0) {

            // ================= KURANGI STOK (FIX UTAMA) =================
            $update = mysqli_query($conn, "
                UPDATE buku 
                SET stok = stok - 1 
                WHERE id_buku = '$id_buku'
            ");

            if (!$update) {
                die("UPDATE ERROR: " . mysqli_error($conn));
            }

            // ================= SIMPAN PEMINJAMAN =================
            $insert = mysqli_query($conn, "
                INSERT INTO peminjaman 
                (id_buku, tanggal_pinjam, tanggal_kembali, status)
                VALUES ('$id_buku', '$tgl_pinjam', '$tgl_kembali', 'pending')
            ");

            if (!$insert) {
                die("INSERT ERROR: " . mysqli_error($conn));
            }

            echo "<script>
                    alert('Buku berhasil dipinjam!');
                    window.location='pinjam.php';
                  </script>";
            exit;

        } else {
            echo "<script>alert('Stok buku habis!'); window.location='pinjam.php';</script>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Buku - Anggota</title>
    <a href="dashboarduser.php" style="
    display:inline-block;
    margin-bottom:15px;
    padding:8px 12px;
    background:#555;
    color:white;
    text-decoration:none;
    border-radius:5px;
">
⬅ Kembali ke Dashboard
</a>

    <!-- AUTO REFRESH + ANTI CACHE -->
    <?php if (!isset($_GET['id'])): ?>
        <meta http-equiv="refresh" content="5">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
    <?php endif; ?>

    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; margin: 0; padding: 20px; color: #333; }
        .container { width: 90%; margin: auto; }
        h2 { margin-bottom: 5px; font-weight: 600; }
        h3 { margin-top: 0; color: #555; }
        .card { width: 220px; background: #fff; padding: 15px; margin: 15px; float: left; border-radius: 12px; box-shadow: 0 6px 15px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s, box-shadow 0.3s; }
        .card:hover { transform: translateY(-7px); box-shadow: 0 10px 20px rgba(0,0,0,0.15); }
        .card img { width: 100%; height: 250px; object-fit: cover; border-radius: 10px; }
        .card h4 { margin: 10px 0; font-size: 1em; height: 45px; overflow: hidden; color: #222; }
        .card p { font-size: 0.85em; color: #555; margin: 4px 0; }
        button, a.button-link { margin-top: 8px; padding: 6px 0; width: 100px; font-size: 0.85em; color: white; border: none; border-radius: 6px; cursor: pointer; display: inline-block; text-align: center; text-decoration: none; background-color: #00ccff; transition: background 0.3s; }
        button:hover, a.button-link:hover { background-color: #0099cc; }
        .stok-habis { background-color: gray; cursor: not-allowed; text-decoration: none; }
        .clear { clear: both; }
    </style>
</head>
<body>

<div class="container">
    <h2>📚 Daftar Buku</h2>
    <hr>

<?php
// ================= FORM MODE =================
if (isset($_GET['id'])) {

    $id_buku = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku='$id_buku'");
    $buku = mysqli_fetch_assoc($query);
?>

    <div class="card" style="width:300px;">
        <img src="img/<?php echo !empty($buku['gambar']) ? $buku['gambar'] : 'mariposa.jpg'; ?>" width="100%">
        <h4><?php echo $buku['judul']; ?></h4>
        <p><strong>Penulis:</strong> <?php echo $buku['penulis']; ?></p>
        <p><strong>Penerbit:</strong> <?php echo $buku['penerbit']; ?></p>
        <p><strong>Tahun:</strong> <?php echo $buku['tahun_terbit']; ?></p>

        <form method="POST">
            <input type="hidden" name="id_buku" value="<?php echo $buku['id_buku']; ?>">

            <input type="date" name="tanggal_pinjam" required>
            <input type="date" name="tanggal_kembali" required>

            <button type="submit" name="pinjam">Pinjam</button>
        </form>

        <a href="pinjam.php" class="button-link">Kembali</a>
    </div>

<?php
} else {

$result = mysqli_query($conn, "SELECT * FROM buku");

while($buku = mysqli_fetch_assoc($result)){

    $id_buku = $buku['id_buku'];
    $stok = $buku['stok'];
?>

    <div class="card">
    <img src="img/<?php echo !empty($buku['gambar']) ? $buku['gambar'] : 'mariposa.jpg'; ?>" width="100%">
    <h4><?php echo $buku['judul']; ?></h4>
    <p><strong>Stok:</strong> <?php echo $stok; ?></p>

        <?php if($stok > 0): ?>
            <a class="button-link" href="pinjam.php?id=<?php echo $id_buku; ?>">
                
                <button>Pinjam</button>
            </a>
        <?php else: ?>
            <button class="stok-habis" disabled>Stok Habis</button>
        <?php endif; ?>
    </div>

<?php } } ?>

    <div class="clear"></div>
</div>

</body>
</html>