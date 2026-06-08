<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$host = "localhost"; $user = "root"; $pass = ""; $db = "neocore_cafe";
$conn = new mysqli($host, $user, $pass, $db);

if (isset($_POST['add_menu'])) {
    $name = $_POST['name']; $category = $_POST['category']; $price = $_POST['price']; $stock = $_POST['stock'];
    $conn->query("INSERT INTO menu_items (name, category, price, stock_status) VALUES ('$name', '$category', $price, $stock)");
    header("Location: menu_management.php");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM menu_items WHERE id = $id");
    header("Location: menu_management.php");
}

$menus = $conn->query("SELECT * FROM menu_items ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Neo Core Coffee - Menu Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container" style="max-width: 1150px; margin-top: 30px;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #edf2f7;">
            <div>
                <h2 style="margin: 0; font-family: 'Georgia', serif; color: #1a202c;">Menu & Price Management</h2>
                <p style="margin: 4px 0 0 0; color: #718096; font-size: 0.9rem;">Kelola daftar produk, kategori harga, dan kapasitas porsi kafe</p>
            </div>
            <a href="index.php" class="btn btn-secondary">← Menu Utama</a>
        </div>

        <div style="display: flex; gap: 30px; flex-wrap: wrap; align-items: flex-start;">
            <div class="pos-form" style="flex: 1; min-width: 300px; padding: 25px;">
                <h3 style="margin-top: 0; margin-bottom: 20px; color: #1a202c;">Tambah Menu Baru</h3>
                <form action="menu_management.php" method="POST">
                    <label>Nama Menu:</label>
                    <input type="text" name="name" required>
                    <label>Harga Jual (Rp):</label>
                    <input type="number" name="price" required>
                    <label>Kategori:</label>
                    <select name="category" required>
                        <option value="Coffee">Coffee</option>
                        <option value="Non-Coffee">Non-Coffee</option>
                        <option value="Food">Food</option>
                    </select>
                    <label>Porsi / Stok Awal:</label>
                    <input type="number" name="stock" required>
                    <button type="submit" name="add_menu" class="btn btn-primary" style="width: 100%; background: #111827;">Tambah Menu</button>
                </form>
            </div>

            <div style="flex: 2.2; background: #ffffff; padding: 25px; border-radius: 14px; border: 1px solid #edf2f7;">
                <table style="margin-top: 10px;">
                    <thead>
                        <tr>
                            <th>Nama Menu</th>
                            <th>Kategori</th>
                            <th>Harga Jual</th>
                            <th>Stok Porsi</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $menus->fetch_assoc()): ?>
                            <tr>
                                <td style="font-weight: 700; color: #1a202c;"><?php echo $row['name']; ?></td>
                                <td><span style="background: #edf2f7; color: #4a5568; padding: 5px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600;"><?php echo $row['category']; ?></span></td>
                                <td style="font-weight: bold;">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                                <td style="font-weight: 600;"><?php echo $row['stock_status']; ?> porsi</td>
                                <td style="text-align: center;">
                                    <a href="menu_management.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Hapus?')" class="btn" style="padding: 6px 12px; background: #fee2e2; color: #dc2626; font-size: 0.82rem; font-weight: bold; border-radius: 6px;">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>