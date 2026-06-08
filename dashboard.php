<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$host = "localhost"; $user = "root"; $pass = ""; $db = "neocore_cafe";
$conn = new mysqli($host, $user, $pass, $db);

$total_sales_res = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE payment_status='Paid'");
$total_sales = $total_sales_res->fetch_assoc()['total'] ?? 0;

$total_orders_res = $conn->query("SELECT COUNT(id) as total FROM orders");
$total_orders = $total_orders_res->fetch_assoc()['total'] ?? 0;

$low_stock_res = $conn->query("SELECT * FROM inventories WHERE quantity <= low_stock_threshold");
$low_stock_count = $low_stock_res->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Neo Core Coffee - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #edf2f7;">
            <div>
                <h1 style="margin: 0; font-size: 1.8rem; font-family: 'Georgia', serif;">Neo Core Coffee</h1>
                <p style="margin: 4px 0 0 0; color: #718096; font-size: 0.95rem;">The Core of Great Coffee</p>
            </div>
            <a href="index.php" class="btn btn-secondary">← Menu Utama</a>
        </div>

        <div class="stats-grid">
            <div class="card" style="border-top: 4px solid #b8924a;">
                <h4 style="margin: 0; color: #718096; text-transform: uppercase; font-size: 0.75rem;">Total Pendapatan</h4>
                <p style="color: #b8924a; font-size: 1.8rem; font-weight: 700; margin: 12px 0 0 0;">Rp <?php echo number_format($total_sales, 0, ',', '.'); ?></p>
            </div>
            <div class="card" style="border-top: 4px solid #4a5568;">
                <h4 style="margin: 0; color: #718096; text-transform: uppercase; font-size: 0.75rem;">Volume Transaksi</h4>
                <p style="color: #2d3748; font-size: 1.8rem; font-weight: 700; margin: 12px 0 0 0;"><?php echo $total_orders; ?> <span style="font-size: 1rem; color: #718096;">Pesanan</span></p>
            </div>
            <div class="card" style="border-top: 4px solid #e53e3e;">
                <h4 style="margin: 0; color: #718096; text-transform: uppercase; font-size: 0.75rem;">Logistik Kritis</h4>
                <p style="color: #e53e3e; font-size: 1.8rem; font-weight: 700; margin: 12px 0 0 0;"><?php echo $low_stock_count; ?> <span style="font-size: 1rem; color: #718096;">Item</span></p>
            </div>
        </div>

        <div style="display: flex; gap: 30px; margin-top: 30px; flex-wrap: wrap;">
            <div style="flex: 1.5; background: #ffffff; padding: 25px; border-radius: 14px; border: 1px solid #edf2f7;">
                <h3 style="margin-top: 0; border-bottom: 1px solid #edf2f7; padding-bottom: 12px; font-size: 1.1rem;">Operational Control Hub</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px;">
                    <a href="pos.php" class="btn btn-primary" style="padding: 15px; background: #111827;">Buka Kasir (POS)</a>
                    <a href="menu_management.php" class="btn btn-secondary" style="padding: 15px; font-weight: bold; border: 1px solid #cbd5e1;">Kelola Menu</a>
                    <a href="inventory.php" class="btn btn-secondary" style="padding: 15px;">Mutasi Gudang</a>
                    <a href="orders.php" class="btn btn-secondary" style="padding: 15px;">Laporan Penjualan</a>
                </div>
            </div>

            <div style="flex: 1; background: #ffffff; padding: 25px; border-radius: 14px; border: 1px solid #edf2f7;">
                <h3 style="margin-top: 0; color: #e53e3e; border-bottom: 1px solid #edf2f7; padding-bottom: 12px; font-size: 1.1rem;">Live Stock Warning</h3>
                <div style="margin-top: 15px;">
                    <?php if($low_stock_count > 0): ?>
                        <?php while($item = $low_stock_res->fetch_assoc()): ?>
                            <div style="background: #f7fafc; padding: 12px; margin-bottom: 10px; border-radius: 8px; border-left: 4px solid #e53e3e; border: 1px solid #edf2f7; border-left: 4px solid #e53e3e;">
                                <strong style="color: #2d3748; font-size: 0.9rem;"><?php echo $item['item_name']; ?></strong>
                                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #718096; margin-top: 5px;">
                                    <span>Sisa: <b style="color: #e53e3e;"><?php echo $item['quantity'].' '.$item['unit']; ?></b></span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="text-align: center; color: #48bb78; padding: 30px 0; font-size: 0.9rem; font-weight: bold;">✨ Logistik aman!</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>