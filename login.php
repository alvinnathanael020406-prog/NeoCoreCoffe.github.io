<?php
session_start();
$host = "localhost"; $user = "root"; $pass = ""; $db = "neocore_cafe";
$conn = new mysqli($host, $user, $pass, $db);

$error = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validasi kecocokan email dan password ke database
    $query = $conn->query("SELECT * FROM users WHERE email='$email' AND password='$password'");
    
    if ($query->num_rows > 0) {
        $user_data = $query->fetch_assoc();
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['user_name'] = $user_data['name'];
        
        // Jika login sukses, arahkan ke halaman gateway utama
        header("Location: index.php");
        exit();
    } else {
        $error = "Email atau Password salah! Silakan coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Neo Core Coffee - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 90vh;">
    <div class="pos-form" style="width: 100%; max-width: 400px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.7);">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="color: #dfb76c; margin: 0; font-family: 'Georgia', serif; font-size: 1.8rem;">Neo Core Coffee</h2>
            <p style="color: #8a8a93; font-size: 0.85rem; margin-top: 5px;">The Core of Great Coffee</p>
        </div>

        <?php if($error != ""): ?>
            <div style="background: rgba(220, 53, 69, 0.15); color: #dc3545; padding: 12px; border-radius: 6px; border: 1px solid #dc3545; margin-bottom: 20px; font-size: 0.9rem; text-align: center;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label>Email Address:</label>
            <input type="email" name="email" placeholder="contoh@neocore.com" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="••••••••" required>

            <button type="submit" name="login" class="btn btn-primary" style="width: 100%; font-weight: bold; margin-top: 10px; padding: 14px;">Sign In</button>
        </form>

        <div style="text-align: center; margin-top: 25px; font-size: 0.9rem; color: #a0a0b2;">
            <a href="forgot_password.php" style="color: #dfb76c;">Forgot Password?</a>
            <p style="margin-top: 10px;">Belum punya akun? <a href="register.php" style="color: #fff; font-weight: bold; text-decoration: underline;">Buat Akun</a></p>
        </div>
    </div>
</body>
</html>