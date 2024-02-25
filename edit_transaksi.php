<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'kasir')) {
    header('Location: login.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['id'])) {
        $id_outlet = $_POST['id_outlet'];
        $kode_invoice = $_POST['kode_invoice'];
        $id_member = $_POST['id_member'];
        $tgl = $_POST['tgl'];
        $batas_waktu = $_POST['batas_waktu'];
        $tgl_bayar = $_POST['tgl_bayar'];
        $biaya_tambahan = $_POST['biaya_tambahan'];
        $diskon = $_POST['diskon'];
        $pajak = $_POST['pajak'];
        $status = $_POST['status'];
        $dibayar = $_POST['dibayar'];
        $id_user = $_SESSION['user_id'];
        $id = $_GET['id'];

        $query = "UPDATE tb_transaksi SET id_outlet = ?, kode_invoice = ?, id_member = ?, tgl = ?, batas_waktu = ?, tgl_bayar = ?, biaya_tambahan = ?, diskon = ?, pajak = ?, status = ?, dibayar = ?, id_user = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isississssssi", $id_outlet, $kode_invoice, $id_member, $tgl, $batas_waktu, $tgl_bayar, $biaya_tambahan, $diskon, $pajak, $status, $dibayar, $id_user, $id);

        if ($stmt->execute()) {
            $message = 'Transaksi berhasil diperbarui.';
            header('Location: crud_transaksi.php');
            exit();
        } else {
            $message = 'Gagal memperbarui transaksi.';
        }

        $stmt->close();
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM tb_transaksi WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaksi = $result->fetch_assoc();

    if (!$transaksi) {
        header('Location: crud_transaksi.php');
        exit();
    }

    $stmt->close();
} else {
    header('Location: crud_transaksi.php');
    exit();
}

$members_result = $conn->query("SELECT id, nama FROM tb_member");
$members = $members_result->fetch_all(MYSQLI_ASSOC);

$outlets_result = $conn->query("SELECT id, nama FROM tb_outlet");
$outlets = $outlets_result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Transaksi</title>
    <link rel="stylesheet" href="assets/entri.css">
</head>

<body>
    <div class="container">
        <h1>Edit Transaksi</h1><br>
        <div class="btn-aksi">
            <a href="crud_transaksi.php">Kembali ke Entri Transaksi</a>
            <a href="dashboard.php">Kembali ke Dashboard</a>
        </div>

        <section class="attendance">
            <div class="attendance-list">
                <?php if ($message) echo "<p>$message</p>"; ?>
                <form action="" method="post">
                    <div class="form-group">
                        <div class="sub">
                        <label for="id_outlet">Outlet:</label>
                            <select name="id_outlet" id="id_outlet" required>
                                <?php foreach ($outlets as $outlet) : ?>
                                    <option value="<?= $outlet['id']; ?>" <?= $outlet['id'] == $transaksi['id_outlet'] ? 'selected' : ''; ?>><?= htmlspecialchars($outlet['nama']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <br>

                    <div class="form-group">
                        <label for="kode_invoice">Kode Invoice:</label><br>
                        <input type="text" id="kode_invoice" name="kode_invoice" value="<?= htmlspecialchars($transaksi['kode_invoice']); ?>" required><br>
                    </div>

                    <div class="form-group">
                        <label for="id_member">Member:</label><br>
                        <select name="id_member" id="id_member" required>
                            <?php foreach ($members as $member) : ?>
                                <option value="<?= $member['id']; ?>" <?= $member['id'] == $transaksi['id_member'] ? 'selected' : ''; ?>><?= htmlspecialchars($member['nama']); ?></option>
                            <?php endforeach; ?>
                        </select><br>
                    </div>

                    <div class="form_group">
                        <label for="tgl">Tanggal:</label><br>
                        <input type="datetime-local" id="tgl" name="tgl" value="<?= date('Y-m-d\TH:i', strtotime($transaksi['tgl'])); ?>" required><br>
                    </div>

                    <div class="form-group">
                        <label for="batas_waktu">Batas Waktu:</label><br>
                        <input type="datetime-local" id="batas_waktu" name="batas_waktu" value="<?= date('Y-m-d\TH:i', strtotime($transaksi['batas_waktu'])); ?>" required><br>
                    </div>

                    <div class="form-group">
                        <label for="tgl_bayar">Tanggal Bayar:</label><br>
                        <input type="datetime-local" id="tgl_bayar" name="tgl_bayar" value="<?= $transaksi['tgl_bayar'] ? date('Y-m-d\TH:i', strtotime($transaksi['tgl_bayar'])) : ''; ?>"><br>
                    </div>

                    <div class="form-group">
                        <label for="biaya_tambahan">Biaya Tambahan:</label><br>
                        <input type="number" id="biaya_tambahan" name="biaya_tambahan" value="<?= $transaksi['biaya_tambahan']; ?>"><br>
                    </div>

                    <div class="form-group">
                        <label for="diskon">Diskon:</label><br>
                        <input type="number" id="diskon" name="diskon" value="<?= $transaksi['diskon']; ?>"><br>
                    </div>

                    <div class="form-group">
                        <label for="pajak">Pajak:</label><br>
                        <input type="number" id="pajak" name="pajak" value="<?= $transaksi['pajak']; ?>"><br>
                    </div>

                    <div class="form-group">
                        <div class="sub">
                            <label for="status">Status:</label><br>
                            <select name="status" id="status">
                                <option value="baru" <?= $transaksi['status'] == 'baru' ? 'selected' : ''; ?>>Baru</option>
                                <option value="proses" <?= $transaksi['status'] == 'proses' ? 'selected' : ''; ?>>Proses</option>
                                <option value="selesai" <?= $transaksi['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                <option value="diambil" <?= $transaksi['status'] == 'diambil' ? 'selected' : ''; ?>>Diambil</option>
                            </select><br>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="sub">
                            <label for="dibayar">Dibayar:</label><br>
                            <select name="dibayar" id="dibayar">
                                <option value="belum_dibayar" <?= $transaksi['dibayar'] == 'belum_dibayar' ? 'selected' : ''; ?>>Belum Dibayar</option>
                                <option value="dibayar" <?= $transaksi['dibayar'] == 'dibayar' ? 'selected' : ''; ?>>Dibayar</option>
                            </select>
                        </div>
                    </div>

                    <br>
                        <button><input type="submit" value="Update Transaksi" style="border:none; width:auto;"></button>
                </form>
                <br>
            </div>
        </section>
    </div>
</body>

</html>