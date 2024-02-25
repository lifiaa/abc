<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'kasir')) {
    header('Location: login.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Insert ke database
    $query = "INSERT INTO tb_transaksi (id_outlet, kode_invoice, id_member, tgl, batas_waktu, tgl_bayar, biaya_tambahan, diskon, pajak, status, dibayar, id_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $result = mysqli_prepare($conn, $query);
    if ($result) {
        mysqli_stmt_bind_param($result, 'isssssdddsii', $id_outlet, $kode_invoice, $id_member, $tgl, $batas_waktu, $tgl_bayar, $biaya_tambahan, $diskon, $pajak, $status, $dibayar, $id_user);
        if (mysqli_stmt_execute($result)) {
            $message = 'Transaksi berhasil ditambahkan.';
        } else {
            $message = 'Gagal menambahkan transaksi.';
        }
        mysqli_stmt_close($result);
    } else {
        $message = 'Gagal menyiapkan statement.';
    }
}

$members = $conn->query("SELECT id, nama FROM tb_member")->fetch_all(MYSQLI_ASSOC);
$outlets = $conn->query("SELECT id, nama FROM tb_outlet")->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Entri Transaksi</title>
    <link rel="stylesheet" href="assets/entri.css">
</head>

<body>
    <div class="container">
        <h1>Entri Transaksi</h1>

        <a href="dashboard.php"><button>Kembali ke Dashboard</button></a><br>

        <section class="attendance">
            <div class="attendance-list">
                <?php if ($message) echo "<p>$message</p>"; ?>
                <form action="" method="post" class="form-entri">
                    <div class="form-group">
                        <div class="sub">
                            <label for="id_outlet">Outlet :</label><br>
                            <select name="id_outlet" id="id_outlet" required>
                                <?php foreach ($outlets as $outlet) : ?>
                                    <option value="<?= $outlet['id']; ?>"><?= $outlet['nama']; ?></option>
                                <?php endforeach; ?>
                            </select><br>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="kode_invoice">Kode Invoice :</label><br>
                        <input type="text" id="kode_invoice" name="kode_invoice" required><br>
                    </div>

                    <div class="form-group">
                        <div class="sub">
                            <label for="id_member">Member :</label><br>
                            <select name="id_member" id="id_member" required>
                                <?php foreach ($members as $member) : ?>
                                    <option value="<?= $member['id']; ?>"><?= $member['nama']; ?></option>
                                <?php endforeach; ?>
                            </select><br>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tgl">Tanggal :</label><br>
                        <input type="datetime-local" id="tgl" name="tgl" value="<?= date('Y-m-d\TH:i', strtotime($transaksi['tgl'])); ?>" required><br>
                    </div>

                    <div class="form-group">
                        <label for="batas_waktu">Batas Waktu :</label><br>
                        <input type="datetime-local" id="batas_waktu" name="batas_waktu" value="<?= date('Y-m-d\TH:i', strtotime($transaksi['batas_waktu'])); ?>" required><br>
                    </div>

                    <div class="form-group">
                        <label for="tgl_bayar">Tanggal Bayar :</label><br>
                        <input type="datetime-local" id="tgl_bayar" name="tgl_bayar" value="<?= $transaksi['tgl_bayar'] ? date('Y-m-d\TH:i', strtotime($transaksi['tgl_bayar'])) : ''; ?>"><br>
                    </div>

                    <div class="form-group">
                        <label for="biaya_tambahan">Biaya Tambahan :</label><br>
                        <input type="number" id="biaya_tambahan" name="biaya_tambahan" value="0"><br>
                    </div>

                    <div class="form-group">
                        <label for="diskon">Diskon (%) :</label><br>
                        <input type="number" id="diskon" name="diskon" value="0"><br>
                    </div>

                    <div class="form-group">
                        <label for="pajak">Pajak (%) :</label><br>
                        <input type="number" id="pajak" name="pajak" value="0"><br>
                    </div>

                    <div class="form-group">
                        <div class="sub">
                            <label for="status">Status :</label><br>
                            <select name="status" id="status" required>
                                <option value="baru">Baru</option>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                                <option value="diambil">Diambil</option>
                            </select><br>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="sub">
                            <label for="dibayar">Dibayar :</label><br>
                            <select name="dibayar" id="dibayar" required>
                                <option value="dibayar">Dibayar</option>
                                <option value="belum_dibayar">Belum Dibayar</option>
                            </select><br>
                        </div>
                    </div>
                    <br>
                    <div class="btnn" style="display:flex;">
                        <button><input type="submit" value="Simpan" style="border:none; width:auto;"></button><br>
                        <a href="crud_transaksi.php"><button>Daftar Transaksi</button></a><br>
                        <a href="edit_transaksi.php"><button>Edit Transaksi</button></a><br>
                    </div>
                </form>
                <br>
            </div>
        </section>
    </div>
</body>

</html>