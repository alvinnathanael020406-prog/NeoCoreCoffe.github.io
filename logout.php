<?php
session_start();

// Menghapus semua data session login
session_destroy();

// Setelah dihapus, lempar user kembali ke halaman login
header("Location: login.php");
exit();
?>