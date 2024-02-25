<?php

include 'koneksi.php';
session_start();

$query = "SELECT * FROM tb_member;";
$sql = mysqli_query($conn, $query);
$no = 0;

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$userRole = $_SESSION['role'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Dashboard | Laundry</title>
    <link rel="stylesheet" href="assets/style.css" />
    <!-- Font Awesome Cdn Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet" />
</head>

<body>
    <div class="container-main">
        <nav>
            <ul>
                <li>
                    <a href="" class="logo">
                        <img src="assets/img/img1.jpg">
                        <span class="nav-item"><?php echo ucfirst($_SESSION['role']); ?></span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="ri-dashboard-line ri"></i>
                        <span class="nav-item">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="crud_paket.php">
                        <i class="ri-list-ordered-2 ri"></i>
                        <span class="nav-item">Paket</span>
                    </a>
                </li>

                <li>
                    <a href="crud_outlet.php">
                        <i class="ri-store-3-fill ri"></i>
                        <span class="nav-item">Outlet</span>
                    </a>
                </li>
                <li>
                    <a href="entri_transaksi.php">
                        <i class="ri-keyboard-fill ri"></i>
                        <span class="nav-item">Entri</span>
                    </a>
                </li>
                <li>
                    <a href="detail_transaksi.php">
                        <i class="ri-bar-chart-fill ri"></i>
                        <span class="nav-item">Detail</span>
                    </a>
                </li>
                <li>
                    <a href="generate.php">
                        <i class="ri-folder-open-fill ri"></i>
                        <span class="nav-item">Generate</span>
                    </a>
                </li>
                <li>
                    <a href="regis_pelanggan.php">
                        <i class="ri-user-shared-2-line ri"></i>
                        <span class="nav-item">Registrasi</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php" class="logout">
                        <i class="ri-logout-box-r-line ri"></i>
                        <span class="nav-item">Log out</span>
                    </a>
                </li>
            </ul>
        </nav>

        <section class="main">
            <div class="main-top">
                <h1>Paket</h1>
                <a href="">
                    <i class="ri-t-shirt-fill" style="font-size:2.5rem; color:grey;"></i>
                </a>
            </div>
            <div class="top">
                <div class="users">
                    <div class="card">
                        <img src="assets/img/kaos.jpg">
                        <h4>Kaos</h4>
                        <div class="per">
                            <span>Rp 10.000.00</span>
                            <span>1 Kg</span>
                        </div>
                        <a href="crud_paket.php"><button>Pesan Paket</button></a>
                    </div>
                    <div class="card">
                        <img src="assets/img/baju.jpg">
                        <h4>Kiloan</h4>
                        <div class="per">
                            <span>Rp 15.000.00</span>
                            <span>1 Kg</span>
                        </div>
                        <a href="crud_paket.php"><button>Pesan Paket</button></a>
                    </div>
                </div>
                <div class="users">
                    <div class="card">
                        <img src="assets/img/selimut.jpg">
                        <h4>Selimut</h4>
                        <div class="per">
                            <span>Rp 20.000.00</span>
                            <span>1 Helai</span>
                        </div>
                        <a href="crud_paket.php"><button>Pesan Paket</button></a>
                    </div>
                    <div class="card">
                        <img src="assets/img/bedcover.jpg">
                        <h4>Bed Cover</h4>
                        <div class="per">
                            <span>Rp 25.000.00</span>
                            <span>1 Helai</span>
                        </div>
                        <a href="crud_paket.php"><button>Pesan Paket</button></a>
                    </div>
                </div>
            </div>

            <br><br>

            <section class="attendance">
                <h2>Pesanan</h2>
                <div class="attendance-list">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Jenis Kelamin</th>
                                <th>Telepon</th>
                                <th>Login Time</th>
                                <th>Logout Time</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($result = mysqli_fetch_assoc($sql)) {
                            ?>
                                <tr>
                                    <td><?php echo $result['id']; ?></td>
                                    <td><?php echo $result['nama']; ?></td>
                                    <td><?php echo $result['alamat']; ?></td>
                                    <td><?php echo $result['jenis_kelamin']; ?></td>
                                    <td><?php echo $result['tlp']; ?></td>
                                    <td>8:00AM</td>
                                    <td>3:00PM</td>
                                    <td><a href="generate.php"><button>View</button></a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
    </div>

</body>

</html>
</span>