<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$message = '';
$edit = false;
$id = $jenis = $nama_paket = $harga = $id_outlet = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $jenis = $_POST['jenis'] ?? '';
    $nama_paket = $_POST['nama_paket'] ?? '';
    $harga = $_POST['harga'] ?? '';
    $id_outlet = $_POST['id_outlet'] ?? '';
    $id = $_POST['id'] ?? '';
    //echo $id;die;
    if (!empty($id)) {
        $query = "UPDATE tb_paket SET jenis = '$jenis', nama_paket = '$nama_paket', harga = '$harga', id_outlet = '$id_outlet' WHERE id = '$id'";
        // $query = "Call paket ($id,'$jenis', '$nama_paket', '$harga', '$id_outlet')";
        // $message = 'Data paket berhasil diperbarui.';
    } else {
        $query = "Call paket ('','$jenis', '$nama_paket', '$harga', '$id_outlet')";
        $message = '<script>alert("Data paket berhasil ditambahkan.")</script>';
    }
    // Execute the query
    $conn->query($query);
} elseif (isset($_GET['action'])) {
    if ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $edit = true;
        $query = "SELECT * FROM tb_paket WHERE id = $id"; // Directly inject $id into the query
        $result = mysqli_query($conn, $query);
        if ($result) {
            $result = mysqli_fetch_assoc($result);
            if ($result) {
                $jenis = $result['jenis'];
                $nama_paket = $result['nama_paket'];
                $harga = $result['harga'];
                $id_outlet = $result['id_outlet'];
            }
        }
    } elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = "DELETE FROM tb_paket WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $message = '<script>alert("Berhasil menghapus data paket.");</script>';
        } else {
            $message = '<script>alert("Gagal menghapus data paket.");</script>';
        }
    }
}


$query = "SELECT p.*, o.nama as nama_outlet FROM tb_paket p JOIN tb_outlet o ON p.id_outlet = o.id";
$result = $conn->query($query);
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
        <h1>CRUD Produk/Paket Cucian</h1><br>
        <div class="btn-aksi">
            <a href="entri_transaksi.php">Kembali ke entri transaksi</a>
            <a href="dashboard.php">Kembali ke Dashboard</a>
        </div>

        <?php if ($message) echo "<p>$message</p>"; ?>
        <form class="form-paket" action="" method="POST">
            <div class="set">
                <label for="id_outlet">Outlet:</label><br>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <select name="id_outlet" id="id_outlet" required>
                    <?php
                    $outlets_result = $conn->query("SELECT * FROM tb_outlet");
                    while ($outlet = $outlets_result->fetch_assoc()) {
                        $selected = ($outlet['id'] == $id_outlet) ? ' selected' : '';
                        echo "<option value='{$outlet['id']}'$selected>{$outlet['nama']}</option>";
                    }
                    ?>
                </select>
            </div>

            <br>

            <div class="set">
                <label for="jenis">Jenis:</label><br>
                <select id="jenis" name="jenis" required>
                    <option value="kiloan" <?php echo $jenis == 'kiloan' ? 'selected' : '' ?>>Kiloan</option>
                    <option value="selimut" <?php echo $jenis == 'selimut' ? 'selected' : '' ?>>Selimut</option>
                    <option value="bedcover" <?php echo $jenis == 'bedcover' ? 'selected' : '' ?>>Bed Cover</option>
                    <option value="kaos" <?php echo $jenis == 'kaos' ? 'selected' : '' ?>>Kaos</option>
                    <option value="lain" <?php echo $jenis == 'lain' ? 'selected' : '' ?>>Lain-lain</option>
                </select>
            </div>
            <br>
            <div class="set">
                <label for="nama_paket" style="width:100%;">Nama Paket:</label><br>
                <input type="text" id="nama_paket" name="nama_paket" value="<?php echo ($nama_paket); ?>" required style="width:auto;"><br>
            </div>
            <div class="set">
                <label for="harga">Harga:</label><br>
                <input type="number" id="harga" name="harga" value="<?php echo ($harga); ?>" required><br><br>
            </div>
            <button><input type="submit" name="save" value="<?= $edit ? 'Update' : 'Simpan' ?>" style="border:none; width:100%;">
            </button>
        </form>

        <br>

        <h2>Daftar Pesanan Paket Cucian</h2>
        <section class="attendance">
            <div class="attendance-list">
                <table class="1 table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Outlet</th>
                            <th>Jenis</th>
                            <th>Nama Paket</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php foreach ($pakets as $paket) : ?>
                        <tr style="text-transform:capitalize;">
                            <td><?php echo $paket['id']; ?></td>
                            <td><?php echo $paket['nama_outlet']; ?></td>
                            <td><?php echo $paket['jenis']; ?></td>
                            <td><?php echo $paket['nama_paket']; ?></td>
                            <td>Rp. <?php echo $paket['harga']; ?>.000,-</td>
                            <td class="btn-aksi">
                                <a href="?action=edit&id=<?= $paket['id']; ?>" onclick="return confirm('Yakin ingin memperbarui data?')">Edit</a>
                                <a href="?action=delete&id=<?= $paket['id']; ?>" onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </section>
    </div>
</body>

</html>