<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $tlp = $_POST['tlp'] ?? '';
    $id = $_POST['id'] ?? '';

    if (!empty($id)) {
        $nama = mysqli_real_escape_string($conn, $nama);
        $alamat = mysqli_real_escape_string($conn, $alamat);
        $tlp = mysqli_real_escape_string($conn, $tlp);
        $id = mysqli_real_escape_string($conn, $id);

        $query = "UPDATE tb_outlet SET nama = '$nama', alamat = '$alamat', tlp = '$tlp' WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            $message = 'Data outlet berhasil diperbarui.';
        } else {
            $message = 'Gagal memperbarui data outlet.';
        }
    } else {
        $nama = mysqli_real_escape_string($conn, $nama);
        $alamat = mysqli_real_escape_string($conn, $alamat);
        $tlp = mysqli_real_escape_string($conn, $tlp);

        $query = "INSERT INTO tb_outlet (nama, alamat, tlp) VALUES ('$nama', '$alamat', '$tlp')";
        if (mysqli_query($conn, $query)) {
            $message = 'Data outlet berhasil ditambahkan.';
        } else {
            $message = 'Gagal menambahkan data outlet.';
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id);
    $query = "DELETE FROM tb_outlet WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $message = 'Data outlet berhasil dihapus.';
    } else {
        $message = 'Gagal menghapus data outlet.';
    }
}

$nama = $alamat = $tlp = $id = '';
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM tb_outlet WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $outlet = mysqli_fetch_assoc($result);
        $nama = $outlet['nama'];
        $alamat = $outlet['alamat'];
        $tlp = $outlet['tlp'];
        $id = $outlet['id'];
    }
}

$query = "SELECT * FROM tb_outlet";
$result = mysqli_query($conn, $query);
$outlets = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CRUD Outlet</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h1>CRUD Outlet</h1><br>
        <div class="btn-aksi">
            <a href="entri_transaksi.php">Kembali ke entri transaksi</a>
            <a href="dashboard.php">Kembali ke Dashboard</a>
        </div>
        <?php if ($message) echo "<p>$message</p>"; ?>
        <form action="crud_outlet.php" method="post" class="form-outlet" class="form-outlet">
            <div class="form-group">
                <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
                <label for="nama">Nama:</label><br>
                <input type="text" id="nama" name="nama" required value="<?php echo $nama ?>"><br>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label><br>
                <textarea id="alamat" name="alamat" required><?php echo $alamat ?></textarea><br>
            </div>
            <div class="form-group">
                <label for="tlp">Telepon:</label><br>
                <input type="text" id="tlp" name="tlp" required value="<?php echo $tlp ?>"><br><br>
            </div>
            <button><input type="submit" value="Simpan" style="border:none;"></button>
        </form>
        <br><br><br>

        <h2>Daftar Outlet</h2>
        <section class="attendance">
            <div class="attendance-list">

                <table class="1 table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Outlet</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($outlets as $outlet) : ?>
                            <tr>
                                <td><?php echo $outlet['id'] ?></td>
                                <td><?php echo $outlet['nama'] ?></td>
                                <td><?php echo $outlet['alamat'] ?></td>
                                <td><?php echo $outlet['tlp'] ?></td>
                                <td class="btn-aksi">
                                    <a href="?action=edit&id=<?= $outlet['id'] ?>">Edit</a>
                                    <a href="?action=delete&id=<?= $outlet['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?'">Hapus</a>
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