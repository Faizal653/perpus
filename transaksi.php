<?php
include 'koneksi.php';

// Tambah transaksi jika form dikirim
if (isset($_POST['simpan'])) {
    $id_buku = mysqli_real_escape_string($conn, $_POST['id_buku']);
    $id_anggota = mysqli_real_escape_string($conn, $_POST['id_anggota']);
    $nama_anggota = mysqli_real_escape_string($conn, $_POST['nama_anggota']);
    $tanggal_pinjam = mysqli_real_escape_string($conn, $_POST['tanggal_pinjam']);
    $tanggal_kembali = mysqli_real_escape_string($conn, $_POST['tanggal_kembali']);

    $getAnggota = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT nama_anggota FROM anggota WHERE id_anggota='$id_anggota'"
));

$nama_anggota = $getAnggota['nama_anggota'] ?? 'Tidak ditemukan';

    // ✅ SATU-SATUNYA INSERT DI SINI
    mysqli_query($conn, "INSERT INTO transaksi 
    (id_anggota, nama_anggota, id_buku, tanggal_pinjam, tanggal_kembali, status) 
    VALUES 
    ('$id_anggota', '$nama_anggota', '$id_buku', '$tanggal_pinjam', '$tanggal_kembali', 'dipinjam')");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; }
        .box { width: 700px; margin: 50px auto; background: white; padding: 20px; border-radius: 10px; }
        input, select, button { width: 100%; padding: 10px; margin-top: 10px; }
        button { background: #28a745; color: white; border: none; cursor: pointer; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #28a745; color: white; }
    </style>
</head>
<body>

<div class="box">
    <h2>📄 Transaksi</h2>

    <!-- FORM TAMBAH TRANSAKSI -->
    <form method="POST">
        <label>Pilih Buku</label>
        <select name="id_buku" required>
            <option value="">-- Pilih Buku --</option>
            <?php
            $buku = mysqli_query($conn, "SELECT * FROM buku");
            while ($b = mysqli_fetch_assoc($buku)) {
                echo "<option value='{$b['id_buku']}'>{$b['judul']} ({$b['penulis']})</option>";
            }
            ?>
        </select>

        <label>Pilih Anggota</label>
        <select name="id_anggota" required>
            <option value="">-- Pilih Anggota --</option>
            <?php
            $anggota = mysqli_query($conn, "SELECT * FROM anggota");
            while ($a = mysqli_fetch_assoc($anggota)) {
                echo "<option value='{$a['id_anggota']}'>{$a['nama_anggota']}</option>";
            }
            ?>
        </select>

        <label>Tanggal Pinjam</label>
        <input type="date" name="tanggal_pinjam" required>

        <label>Tanggal Kembali</label>
        <input type="date" name="tanggal_kembali" required>

        <button name="simpan">Simpan</button>
    </form>

    <!-- TABEL TRANSAKSI -->
    <table>
        <tr>
            <th>No</th>
            <th>Buku</th>
            <th>Nama Anggota</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
        </tr>

        <?php
        $no = 1;
        $data = mysqli_query($conn, "SELECT 
    id_transaksi,
    id_buku,
    nama_anggota,
    tanggal_pinjam,
    tanggal_kembali,
    status
FROM transaksi
ORDER BY id_transaksi DESC");

        while ($row = mysqli_fetch_assoc($data)) {

            // ✅ ambil nama buku
            $buku_nama = mysqli_fetch_assoc(mysqli_query($conn, 
                "SELECT judul FROM buku WHERE id_buku = '{$row['id_buku']}'"
            ));

            echo "<tr>
                    <td>$no</td>
                    <td>".htmlspecialchars($buku_nama['judul'] ?? '-')."</td>
                    <td>".htmlspecialchars($row['nama_anggota'] ?? '-')."</td>
                    <td>".htmlspecialchars($row['tanggal_pinjam'])."</td>
                    <td>".(!empty($row['tanggal_kembali']) ? htmlspecialchars($row['tanggal_kembali']) : '-')."</td>
                    <td>".htmlspecialchars($row['status'])."</td>
                  </tr>";
            $no++;
        }
        ?>
    </table>
</div>

</body>
</html>