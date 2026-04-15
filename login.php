<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role']; // admin / anggota

    if ($role == "admin") {
        $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
        $user  = mysqli_fetch_assoc($query);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin'] = $user['nama_admin']; // simpan session admin
            header("Location: dasboard.php");
            exit;
        } else {
            echo "<script>alert('Username atau password salah (admin)');</script>";
        }
    } else {
        $query = mysqli_query($conn, "SELECT * FROM anggota WHERE username='$username'");
        $user  = mysqli_fetch_assoc($query);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['anggota'] = $user['nama_lengkap']; // simpan session anggota
            header("Location: dashboarduser.php");
            exit;
        } else {
            echo "<script>alert('Username atau password salah (anggota)');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Perpustakaan</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .login-box { width: 350px; margin: 100px auto; padding: 30px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.2); border-radius: 10px; }
        h2 { text-align: center; }
        input, select, button { width: 100%; padding: 10px; margin-top: 10px; }
        button { background: #28a745; color: white; border: none; }
        button:hover { background: #218838; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login Perpustakaan</h2>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Login Sebagai</label>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="anggota">Anggota</option>
        </select>

        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>