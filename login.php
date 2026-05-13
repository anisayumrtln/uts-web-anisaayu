<?php
session_start();
include 'koneksi.php'; 

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");

    if(mysqli_num_rows($cek) > 0){
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username; 
        
        header("location:dashboard.php");
        exit;
    } else {
        echo "<script>alert('Username atau password salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Minimarket Anisa</title>
    <style>
        body { font-family: 'Courier New', monospace; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border: 2px solid #333; box-shadow: 10px 10px 0px #333; width: 300px; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #333; box-sizing: border-box; font-family: monospace; }
        button { width: 100%; padding: 10px; background: #1E104E; color: white; border: none; cursor: pointer; font-family: monospace; font-weight: bold; }
        button:hover { background: #28a745; }
        .error { color: red; font-size: 12px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 style="margin-top: 0;">LOGIN KASIR</h2>
        <hr>
        <?php if(isset($error)) : ?>
            <div class="error">Username atau Password salah!</div>
        <?php endif; ?>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required autocomplete="off">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">MASUK KE SISTEM</button>
        </form>
    </div>
</body>
</html>