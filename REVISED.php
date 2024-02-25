<?php
session_start();
require_once 'koneksi.php';

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$message = '';

// Delete record if action is specified and ID is provided
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM tb_paket WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $message = '<script>alert("Data paket berhasil dihapus.");</script>';
    } else {
        $message = 'Gagal menghapus data paket.';
    }
}

$query = "SELECT tb_detail_transaksi.*, tb_paket.nama AS nama_paket 
              FROM tb_detail_transaksi 
              LEFT JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id 
              WHERE tb_detail_transaksi.id_transaksi = ?";
$result = $conn->query($query);

// Fetch all records
$pakets = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CRUD Produk/Paket Cucian</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h1>CRUD Produk/Paket Cucian</h1>

        <?php if ($message) echo "<p>$message</p>"; ?>
        <form action="" method="POST">
            <!-- Form inputs -->
        </form>

        <br>

        <a href="dashboard.php">Kembali ke Dashboard</a>
        <h2>Daftar Paket Cucian</h2>
        <table class="1 table">
            <!-- Table headers -->
            <?php foreach ($pakets as $paket) : ?>
                <!-- Table rows -->
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>