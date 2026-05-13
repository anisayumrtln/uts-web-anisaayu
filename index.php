<!DOCTYPE html>
<html>
<head>
    <title>Selamat Datang - Kasir Minimarket</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .welcome-box {
            background-color: white;
            padding: 40px;
            border: 2px solid black;
            box-shadow: 10px 10px 0px #1E104E; /* Shadow ungu tua */
            text-align: center;
            max-width: 500px;
        }
        h1 { color: #1E104E; margin-bottom: 10px; }
        p { color: #666; margin-bottom: 30px; }
        .btn-login {
            background-color: #1E104E;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            font-weight: bold;
            border: 2px solid black;
            display: inline-block;
            transition: 0.3s;
        }
        .btn-login:hover {
            background-color: #331a7a;
            box-shadow: 5px 5px 0px #000;
        }
    </style>
</head>
<body>
    <div class="welcome-box">
        <h1>SISTEM KASIR MINIMARKET</h1>
        <p>Selamat datang di aplikasi pengelolaan data produk dan transaksi. Silakan masuk untuk mulai bekerja.</p>
        <a href="login.php" class="btn-login">MASUK KE SISTEM</a>
    </div>
</body>
</html>