<?php
session_start();
require_once "koneksi.php";

$message = "Masukkan Username dan password di bawah ini";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil nilai dari form
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Memastikan input tidak kosong
    if (!empty($username) && !empty($password)) {
        // Menggunakan prepared statement untuk menghindari SQL injection
        $sql = "SELECT * FROM tb_user WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Mengambil data user dari hasil query
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            if (in_array($user['role'], ['admin', 'kasir', 'owner'])) {
                header("Location: dashboard.php");
                exit;
            }
        } else {
            $message = "Username atau password salah.";
        }
    } else {
        $message = "Masukkan username atau password terlebih dahulu.";
    }
}
?>



<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <script>
        alert("<?php echo $message; ?>")
    </script>
    <div class="container">
        <section class="attendance">
            <h2>Login</h2>
            <div class="attendance-list">
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label><br>
                        <input type="text" id="username" name="username" required><br>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label><br>
                        <input type="password" id="password" name="password" required><br>
                    </div>
                    <button type="submit" value="Login">Login</button>
                </form>
            </div>
        </section>
    </div>
</body>

</html>