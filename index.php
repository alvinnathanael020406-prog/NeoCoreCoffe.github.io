<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$host = "localhost"; $user = "root"; $pass = ""; $db = "neocore_cafe";
$conn = new mysqli($host, $user, $pass, $db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Neo Core Coffee - Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-color: #f9fafb; margin: 0; font-family: 'Segoe UI', sans-serif;">

    <div style="background: #ffffff; padding: 20px 40px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="margin: 0; font-size: 1.5rem; color: #111827; font-family: 'Georgia', serif;">Neo Core Coffee</h1>
            <p style="margin: 0; color: #6b7280; font-size: 0.85rem;">The Core of Great Coffee</p>
        </div>
        <div style="text-align: right;">
            <p style="margin: 0; font-size: 0.9rem; color: #374151;">Halo, <b><?php echo $_SESSION['user_name']; ?></b></p>
            <a href="logout.php" style="color: #dc2626; font-size: 0.8rem; text-decoration: none;">Keluar Sistem</a>
        </div>
    </div>

    <div style="padding: 40px; max-width: 1400px; margin: auto;">
        
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 40px; align-items: start;">
            
            <div style="border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=1000" alt="Coffee" style="width: 100%; height: 500px; object-fit: cover; display: block;">
            </div>

            <div>
                <h2 style="margin-top: 0; color: #111827;">Pilih Operasional</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <a href="dashboard.php" style="background: #ffffff; padding: 30px; border-radius: 16px; border: 1px solid #e5e7eb; text-decoration: none; color: #111827; transition: 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                        <h3 style="margin: 0 0 10px 0;">Dashboard</h3>
                        <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">Lihat statistik penjualan & stok kritis.</p>
                    </a>
                    <a href="pos.php" style="background: #111827; padding: 30px; border-radius: 16px; text-decoration: none; color: #ffffff; transition: 0.3s;">
                        <h3 style="margin: 0 0 10px 0;">POS Kasir</h3>
                        <p style="margin: 0; color: #d1d5db; font-size: 0.9rem;">Input pesanan baru hari ini.</p>
                    </a>
                    <a href="menu_management.php" style="background: #ffffff; padding: 30px; border-radius: 16px; border: 1px solid #e5e7eb; text-decoration: none; color: #111827;">
                        <h3 style="margin: 0 0 10px 0;">Kelola Menu</h3>
                        <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">Atur harga dan list produk.</p>
                    </a>
                    <a href="inventory.php" style="background: #ffffff; padding: 30px; border-radius: 16px; border: 1px solid #e5e7eb; text-decoration: none; color: #111827;">
                        <h3 style="margin: 0 0 10px 0;">Inventory</h3>
                        <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">Pantau stok bahan baku.</p>
                    </a>
                    <a href="orders.php" style="grid-column: span 2; background: #ffffff; padding: 30px; border-radius: 16px; border: 1px solid #e5e7eb; text-decoration: none; color: #111827;">
                        <h3 style="margin: 0 0 10px 0;">Riwayat Transaksi</h3>
                        <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">Cek seluruh laporan penjualan secara lengkap.</p>
                    </a>
                </div>
            </div>

        </div>
    </div>
</body>
</html>