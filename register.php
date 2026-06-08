<?php
$host = "localhost"; $user = "root"; $pass = ""; $db = "neocore_cafe";
$conn = new mysqli($host, $user, $pass, $db);

$success = "";
$error = "";

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah email sudah terdaftar
    $check_email = $conn->query("SELECT * FROM users WHERE email='$email'");
    
    if ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } elseif ($check_email->num_rows > 0) {
        $error = "Email ini sudah digunakan oleh akun lain!";
    } else {
        // PERBAIKAN: Kolom role_id dihapus dari query INSERT agar tidak memicu SQL Error jika tabel belum di-alter
        $conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
        
        echo "<script>alert('Pendaftaran Berhasil! Silakan masuk.'); window.location='login.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Neo Core Coffee - Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 95vh;">
    <div class="pos-form" style="width: 100%; max-width: 450px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.7);">
        <div style="text-align: center; margin-bottom: 25px;">
            <h2 style="color: #dfb76c; margin: 0; font-family: 'Georgia', serif;">Create Account</h2>
            <p style="color: #8a8a93; font-size: 0.85rem; margin-top: 5px;">Daftarkan akun staf baru Neo Core Coffee</p>
        </div>

        <?php if($error != ""): ?>
            <div style="background: rgba(220, 53, 69, 0.15); color: #dc3545; padding: 12px; border-radius: 6px; border: 1px solid #dc3545; margin-bottom: 20px; font-size: 0.9rem; text-align: center;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <label>Nama Lengkap:</label>
            <input type="text" name="name" placeholder="Masukkan nama Anda" required>

            <label>Email Address:</label>
            <input type="email" name="email" placeholder="nama@neocore.com" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="Buat password unik" required>

            <label>Konfirmasi Password:</label>
            <input type="password" name="confirm_password" placeholder="Ulangi password" required>

            <button type="submit" name="register" class="btn btn-primary" style="width: 100%; font-weight: bold; margin-top: 10px; padding: 14px;">Register Account</button>
        </form>

        <div style="text-align: center; margin-top: 20px; font-size: 0.9rem; color: #a0a0b2;">
            Sudah memiliki akun? <a href="login.php" style="color: #dfb76c; font-weight: bold;">Sign In disini</a>
        </div>
    </div>
</body>
</html>