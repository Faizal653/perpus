<?php
session_start();
include 'koneksi.php';

// ==================== PROSES TAMBAH ANGGOTA ====================
$message = "";

if (isset($_POST['tambah'])) {
    $username   = $_POST['username'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama       = $_POST['nama'];
    $alamat     = $_POST['alamat'];
    $telepon    = $_POST['telepon'];

    // Cek apakah username sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM anggota WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $message = "❌ Username '$username' sudah terdaftar!";
    } else {
        $query = "INSERT INTO anggota (username, password, nama_lengkap, alamat, nomor_telepon) 
                  VALUES ('$username','$password','$nama','$alamat','$telepon')";
        if (mysqli_query($conn, $query)) {
            $message = "✅ Anggota '$nama' berhasil ditambahkan!";
        } else {
            $message = "❌ Gagal tambah anggota: " . mysqli_error($conn);
        }
    }
}

// Ambil data anggota untuk tabel
$anggota_list = mysqli_query($conn, "SELECT * FROM anggota ORDER BY id_anggota ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Anggota</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; margin:0; padding:20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        form input, form button { width: 100%; padding: 10px; margin: 5px 0; }
        form button { background: #007bff; color: white; border: none; cursor: pointer; border-radius: 5px; }
        form button:hover { background: #0056b3; }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #007bff; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2>Tambah Anggota</h2>

    <!-- Pesan sukses/error -->
    <?php if($message != ""): ?>
        <div class="message <?php echo (strpos($message,'✅')===0)?'success':'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Form tambah anggota -->
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <input type="text" name="alamat" placeholder="Alamat" required>
        <input type="text" name="telepon" placeholder="Nomor Telepon" required>
        <button name="tambah">Tambah Anggota</button>
    </form>

    <!-- Tabel anggota -->
    <table>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Nama Lengkap</th>
            <th>Alamat</th>
            <th>Telepon</th>
        </tr>
        <?php 
        $no = 1;
        while($row = mysqli_fetch_assoc($anggota_list)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['nama_lengkap']; ?></td>
                <td><?php echo $row['alamat']; ?></td>
                <td><?php echo $row['nomor_telepon']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>