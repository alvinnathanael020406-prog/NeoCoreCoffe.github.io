<?php
session_start();
// Proteksi Keamanan: Jika belum login, lempar kembali ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost"; $user = "root"; $pass = ""; $db = "neocore_cafe";
$conn = new mysqli($host, $user, $pass, $db);

// FITUR BARU: Proses Hapus History Transaksi
if (isset($_GET['delete_order'])) {
    $order_id = $_GET['delete_order'];
    
    // 1. Hapus detail order terlebih dahulu (Foreign Key Constraint)
    $conn->query("DELETE FROM order_details WHERE order_id = $order_id");
    // 2. Hapus data order utama
    $conn->query("DELETE FROM orders WHERE id = $order_id");
    
    echo "<script>alert('History transaksi berhasil dihapus!'); window.location='orders.php';</script>";
}

// 1. QUERY UNTUK DIAGRAM: Hitung total quantity terjual per produk
$chart_query = $conn->query("
    SELECT mi.name as product_name, SUM(od.quantity) as total_qty 
    FROM order_details od
    JOIN menu_items mi ON od.menu_item_id = mi.id
    JOIN orders o ON od.order_id = o.id
    WHERE o.payment_status = 'Paid'
    GROUP BY od.menu_item_id
    ORDER BY total_qty DESC
");

$product_names = [];
$product_sales = [];

while ($row = $chart_query->fetch_assoc()) {
    $product_names[] = $row['product_name'];
    $product_sales[] = $row['total_qty'];
}

// 2. QUERY UNTUK TABEL: Ambil semua riwayat transaksi
$table_query = $conn->query("
    SELECT o.id as order_id, o.created_at, o.total_price, mi.name as product_name, od.quantity
    FROM orders o
    JOIN order_details od ON o.id = od.order_id
    JOIN menu_items mi ON od.menu_item_id = mi.id
    ORDER BY o.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Neo Core Coffee - Orders History</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container" style="max-width: 1000px; margin-top: 30px;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #edf2f7;">
            <div>
                <h2 style="margin: 0; font-family: 'Georgia', serif;">Orders & Sales Analytics</h2>
                <p style="margin: 4px 0 0 0; color: #718096; font-size: 0.9rem;">Pantau riwayat transaksi dan performa penjualan produk tertinggi</p>
            </div>
            <a href="index.php" class="btn btn-secondary">← Menu Utama</a>
        </div>

        <div style="background: #ffffff; padding: 25px; border-radius: 16px; border: 1px solid #edf2f7; box-shadow: 0 4px 12px rgba(0,0,0,0.01); margin-bottom: 35px;">
            <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.1rem; color: #1a202c;">📊 Grafik Produk Terlaris (Total Porsi Terjual)</h3>
            <div style="max-height: 350px; position: relative;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div style="background: #ffffff; padding: 25px; border-radius: 16px; border: 1px solid #edf2f7;">
            <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1rem; color: #1a202c;">📋 Log Transaksi Keseluruhan</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID Order</th>
                        <th>Tanggal & Waktu</th>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Total Bayar</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($table_query->num_rows > 0): ?>
                        <?php while($row = $table_query->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?php echo $row['order_id']; ?></strong></td>
                                <td style="color: #718096; font-size: 0.9rem;"><?php echo $row['created_at']; ?></td>
                                <td><span style="font-weight: 600; color: #2d3748;"><?php echo $row['product_name']; ?></span></td>
                                <td><?php echo $row['quantity']; ?> porsi</td>
                                <td style="font-weight: bold; color: #2d3748;">Rp <?php echo number_format($row['total_price'], 0, ',', '.'); ?></td>
                                <td style="text-align: center;">
                                    <a href="orders.php?delete_order=<?php echo $row['order_id']; ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus history transaksi ini?')" 
                                       class="btn" 
                                       style="padding: 6px 12px; background: #fee2e2; color: #dc2626; font-size: 0.82rem; font-weight: bold; border-radius: 6px; display: inline-block;">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #718096; padding: 40px 0;">Belum ada riwayat transaksi masuk.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        const labelsData = <?php echo json_encode($product_names); ?>;
        const salesData = <?php echo json_encode($product_sales); ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsData,
                datasets: [{
                    label: 'Jumlah Porsi Terjual',
                    data: salesData,
                    backgroundColor: '#111827',
                    borderColor: '#111827',
                    borderWidth: 1,
                    borderRadius: 6,
                    barThickness: 35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#edf2f7' },
                        ticks: { stepSize: 1, color: '#718096' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#2d3748', font: { weight: '600' } }
                    }
                }
            }
        });
    </script>
</body>
</html>