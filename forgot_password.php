<?php
$host = "localhost"; $user = "root"; $pass = ""; $db = "neocore_cafe";
$conn = new mysqli($host, $user, $pass, $db);

$info = "";
$error = "";

if (isset($_POST['recover'])) {
    $email = $_POST['email'];
    
    // Cek apakah email ada di database
    $query = $conn->query("SELECT password FROM users WHERE email='$email'");
    
    if ($query->num_rows > 0) {
        $user_data = $query->fetch_assoc();
        $password_lama = $user_data['password'];
        $info = "Email Terverifikasi! Password akun Anda adalah: <b style='color:#dfb76c; font-size:1.1rem;'>" . $password_lama . "</b>";
    } else {
        $error = "Maaf, alamat email tersebut tidak terdaftar di sistem kami.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Neo Core Coffee - Recovery</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 90vh;">
    <div class="pos-form" style="width: 100%; max-width: 420px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.7);">
        <div style="text-align: center; margin-bottom: 25px;">
            <h2 style="color: #dfb76c; margin: 0; font-family: 'Georgia', serif;">Reset Password</h2>
            <p style="color: #8a8a93; font-size: 0.85rem; margin-top: 5px;">Masukkan email terdaftar untuk memulihkan kredensial akses</p>
        </div>

        <?php if($error != ""): ?>
            <div style="background: rgba(220, 53, 69, 0.15); color: #dc3545; padding: 12px; border-radius: 6px; border: 1px solid #dc3545; margin-bottom: 20px; font-size: 0.9rem; text-align: center;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if($info != ""): ?>
            <div style="background: rgba(40, 167, 69, 0.15); color: #28a745; padding: 15px; border-radius: 6px; border: 1px solid #28a745; margin-bottom: 20px; font-size: 0.95rem; text-align: center; line-height: 1.5;">
                <?php echo $info; ?>
            </div>
        <?php endif; ?>

        <form action="forgot_password.php" method="POST">
            <label>Masukkan Email Terdaftar:</label>
            <input type="email" name="email" placeholder="nama@neocore.com" required>

            <button type="submit" name="recover" class="btn btn-primary" style="width: 100%; font-weight: bold; margin-top: 10px; padding: 14px;">Cek Akun Saya</button>
        </form>

        <div style="text-align: center; margin-top: 25px; font-size: 0.9rem;">
            <a href="login.php" style="color: #a0a0b2;">← Kembali ke Halaman Login</a>
        </div>
    </div>
</body>
</html>