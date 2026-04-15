<?php
session_start();
include 'koneksi.php';

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if($data){
        $_SESSION['admin'] = $data['username'];

        // ⬇️ INI YANG PENTING
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Login gagal!";
    }
}
?>