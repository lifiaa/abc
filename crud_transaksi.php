<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'kasir')) {
    header('Location: login.php');
    exit();
}

// Membuat koneksi ke database
$conn = mysqli_connect($host, $user, $pass, $db);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM tb_transaksi WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$query = "SELECT * FROM tb_transaksi";
$result = $conn->query($query);
$transaksi = $result->fetch_all(MYSQLI_ASSOC);

// Tutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Daftar Transaksi</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h1>Daftar Transaksi</h1><br>
        <div class="btn-aksi">
            <a href="entri_transaksi.php">Kembali ke entri transaksi</a>
            <a href="dashboard.php">Kembali ke Dashboard</a>
        </div>

        <section class="attendance">
            <div class="attendance-list">
                <table class="1 table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Outlet</th>
                            <th>Kode Invoice</th>
                            <th>ID Member</th>
                            <th>Tanggal</th>
                            <th>Batas Waktu</th>
                            <th>Tanggal Bayar</th>
                            <th>Biaya Tambahan</th>
                            <th>Diskon (%)</th>
                            <th>Pajak (%)</th>
                            <th>Status</th>
                            <th>Dibayar</th>
                            <th>ID User</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transaksi as $t) : ?>
                            <tr>
                                <td><?= htmlspecialchars($t['id']) ?></td>
                                <td><?= htmlspecialchars($t['id_outlet']) ?></td>
                                <td><?= htmlspecialchars($t['kode_invoice']) ?></td>
                                <td><?= htmlspecialchars($t['id_member']) ?></td>
                                <td><?= htmlspecialchars($t['tgl']) ?></td>
                                <td><?= htmlspecialchars($t['batas_waktu']) ?></td>
                                <td><?= htmlspecialchars($t['tgl_bayar']) ?></td>
                                <td>Rp. <?= htmlspecialchars($t['biaya_tambahan']) ?>.000,-</td>
                                <td><?= htmlspecialchars($t['diskon']) ?>%</td>
                                <td><?= htmlspecialchars($t['pajak']) ?>%</td>
                                <td><?= htmlspecialchars($t['status']) ?></td>
                                <td><?= htmlspecialchars($t['dibayar']) ?></td>
                                <td><?= htmlspecialchars($t['id_user']) ?></td>
                                <td class="btn-aksi">
                                    <a href="edit_transaksi.php?id=<?= $t['id'] ?>">Edit</a>
                                    <a href="?aksi=hapus&id=<?= $t['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
    </div>
</body>

</html>
