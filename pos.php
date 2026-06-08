<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost"; $user = "root"; $pass = ""; $db = "neocore_cafe";
$conn = new mysqli($host, $user, $pass, $db);

if (isset($_POST['checkout'])) {
    $item_id = $_POST['menu_item_id'];
    $qty = $_POST['quantity'];
    
    $menu_res = $conn->query("SELECT name, price, stock_status FROM menu_items WHERE id = $item_id");
    $menu_data = $menu_res->fetch_assoc();
    $price = $menu_data['price'];
    $current_stock = $menu_data['stock_status'];
    $menu_name = $menu_data['name'];
    
    if ($qty > $current_stock) {
        echo "<script>alert('Gagal! Stok untuk menu \"$menu_name\" tidak mencukupi. Sisa stok saat ini hanya: $current_stock porsi.'); window.location='pos.php';</script>";
        exit(); 
    }

    $tax = 0.11; 
    $subtotal = $price * $qty;
    $total_tax = $subtotal * $tax;
    $total_final = $subtotal + $total_tax;

    $conn->query("INSERT INTO orders (total_price, payment_status) VALUES ($total_final, 'Paid')");
    $order_id = $conn->insert_id;

    $conn->query("INSERT INTO order_details (order_id, menu_item_id, quantity, subtotal) VALUES ($order_id, $item_id, $qty, $subtotal)");
    $conn->query("UPDATE menu_items SET stock_status = stock_status - $qty WHERE id = $item_id");

    echo "<script>alert('Transaksi Berhasil! Total: Rp " . number_format($total_final, 0, ',', '.') . "'); window.location='pos.php';</script>";
}

$menus = $conn->query("SELECT * FROM menu_items WHERE stock_status > 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NeoCore Coffee - POS (Cashier)</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container" style="max-width: 1050px; margin-top: 30px;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #edf2f7;">
            <div>
                <h2 style="margin: 0; font-family: 'Georgia', serif;">POS & Order Management</h2>
                <p style="margin: 4px 0 0 0; color: #718096; font-size: 0.9rem;">Input pesanan kasir dengan validasi stok real-time</p>
            </div>
            <a href="index.php" class="btn btn-secondary">← Menu Utama</a>
        </div>
        
        <div style="display: flex; gap: 40px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; text-align: center; min-width: 300px;">
                <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=600" alt="Cafe POS" style="width: 100%; max-width: 450px; border-radius: 14px; box-shadow: 0 8px 20px rgba(0,0,0,0.04); border: 1px solid #edf2f7;">
            </div>

            <div class="pos-form" style="flex: 1.2; min-width: 320px; padding: 30px;">
                <h3 style="margin-top: 0; margin-bottom: 25px; color: #1a202c;">Input Pesanan Baru</h3>
                <form action="pos.php" method="POST">
                    <label>Pilih Menu & Lihat Sisa Porsi:</label>
                    <select name="menu_item_id" required>
                        <option value="" disabled selected>-- Pilih Menu Kopi / Makanan --</option>
                        <?php while($row = $menus->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo $row['name']; ?> - Rp <?php echo number_format($row['price'], 0, ',', '.'); ?> (Sisa Stok: <?php echo $row['stock_status']; ?> porsi)
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label>Jumlah Beli (Quantity):</label>
                    <input type="number" name="quantity" min="1" value="1" required>

                    <button type="submit" name="checkout" class="btn btn-primary" style="width: 100%; font-weight: bold; padding: 14px; background: #111827;">Proses & Bayar Transaksi</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>