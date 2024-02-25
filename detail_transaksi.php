<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'kasir')) {
    header('Location: login.php');
    exit();
}

$message = '';

$pakets = [];
$result = $conn->query("SELECT id, nama_paket, harga FROM tb_paket");
while ($row = $result->fetch_assoc()) {
    $pakets[] = $row;
}

$transaksis = [];
$result = $conn->query("SELECT id FROM tb_transaksi");
while ($row = $result->fetch_assoc()) {
    $transaksis[] = $row;
}

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transaksi = $_POST['id_transaksi'];
    $id_paket = $_POST['id_paket'];
    $qty = $_POST['qty'];
    $keterangan = $_POST['keterangan'];

    if ($_POST['action'] == 'add') {
        $sql = "INSERT INTO tb_detail_transaksi (id_transaksi, id_paket, qty, keterangan) VALUES ('$id_transaksi', '$id_paket', '$qty', '$keterangan')";
        $result = $conn->query($sql);
        $message = $result ? '<script>alert("Detail Transaksi berhasil ditambahkan.")</script>' : '<script>alert("Gagal menambahkan Detail Transaksi.")</script>';
    } elseif ($_POST['action'] == 'edit') {
        $sql = "UPDATE tb_detail_transaksi SET id_transaksi = '$id_transaksi', id_paket = '$id_paket', qty = '$qty', keterangan = '$keterangan' WHERE id = '$id'";
        $result = $conn->query($sql);
        $message = $result ? '<script>alert("Detail Transaksi berhasil diperbarui.")<?script>' : '<script>alert("Gagal memperbarui Detail Transaksi.")<?script>';
    }
}

if ($action == 'delete' && !empty($id)) {
    $sql = "DELETE FROM tb_detail_transaksi WHERE id = '$id'";
    $result = $conn->query($sql);
    $message = $result ? '<script>alert("Detail Transaksi berhasil dihapus.")<?script>' : '<script>alert("Gagal menghapus Detail Transaksi.")<?script>';
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$allTransaksi = [];
$result = $conn->query("SELECT * FROM tb_detail_transaksi");
while ($row = $result->fetch_assoc()) {
    $allTransaksi[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h1 class="mb-4">Detail Transaksi</h1><br>
        <div class="btn-aksi">
            <a href="generate.php">Kembali ke Generate laporan</a>
            <a href="dashboard.php">Kembali ke Dashboard</a>
        </div>
        <?php if (!empty($message)) echo "<div class='alert alert-info'>$message</div>"; ?>
        <section class="attendance">
            <div class="attendance-list">
                <form action="" method="post" class="mb-5">
                    <input type="hidden" name="action" value="<?= empty($editData) ? 'add' : 'edit' ?>">
                    <?php if (!empty($editData)) : ?>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="id_transaksi">Transaksi:</label>
                        <select name="id_transaksi" id="id_transaksi" class="form-control" required>
                            <?php foreach ($transaksis as $transaksi) : ?>
                                <option value="<?= $transaksi['id'] ?>" <?= !empty($editData) && $editData['id_transaksi'] == $transaksi['id'] ? 'selected' : '' ?>><?= $transaksi['id'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_paket">Paket:</label>
                        <select name="id_paket" id="id_paket" class="form-control" required>
                            <?php foreach ($pakets as $paket) : ?>
                                <option value="<?= $paket['id'] ?>" <?= !empty($editData) && $editData['id_paket'] == $paket['id'] ? 'selected' : '' ?>>
                                    <?= $paket['nama_paket'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="qty">Jumlah:</label>
                        <input type="number" class="form-control" id="qty" name="qty" required value="<?= $editData['qty'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="qty">Harga:</label>
                        <input type="number" class="form-control" id="qty" name="qty" required value="<?= $editData['qty'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan:</label>
                        <textarea class="form-control" id="keterangan" name="keterangan"><?= $editData['keterangan'] ?? '' ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary"><?= empty($editData) ? 'Tambah' : 'Update' ?></button>
                </form>
            </div>
        </section>

        <br><br><br>

        <h2>Daftar Detail Transaksi</h2><br>
        <section class="attendance">
            <div class="attendance-list">
                <table class="1 table ">
                    <thead class="thead-dark text-align-center">
                        <tr>
                            <th>Id Transaksi</th>
                            <th>Paket</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allTransaksi as $transaksi) : ?>
                            <tr>
                                <td><?= htmlspecialchars($transaksi['id_transaksi']) ?></td>
                                <td><?= ($paket['nama_paket']) ?></td>
                                <td><?= htmlspecialchars($transaksi['qty']) ?></td>
                                <td>Rp. <?= ($paket['harga']) ?>.000,-</td>
                                <td><?= htmlspecialchars($transaksi['keterangan']) ?></td>
                                <td class="btn-aksi">
                                    <a href="?action=edit&id=<?= $transaksi['id'] ?>">Edit</a>
                                    <a href="?action=delete&id=<?= $transaksi['id'] ?>" onclick="return confirm('Yakin ingin mneghapus data?');">Hapus</a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</body>

</html>