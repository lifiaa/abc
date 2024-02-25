<?php
session_start();
require_once "koneksi.php";

$message = "Masukkan Username dan password di bawah ini";

$ipclient = $_SERVER['REMOTE_ADDR'];
$browser = $_SERVER['HTTP_USER_AGENT'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($username) && isset($password)) {
        // $sql = "SELECT * FROM tb_user WHERE username = '$username' AND password = '$password'";
        // echo $username . "|" . $password;die;
        $result = $conn->query("Call login('".MD5($username)."','".MD5($password)."')");
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            if (in_array($user['role'], ['admin', 'kasir', 'owner'])) {
                //SAVE LOGIN LOG
                $conn2->query("Call login_log('$username','$password','$ipclient','$browser','SUKSES')");
                header("Location: dashboard.php");
                exit;
            }
        } else {
            $conn2->query("Call login_log('$username','$password','$ipclient','$browser','GAGAL')");
            $message = "Username atau password salah.";
            // echo "<script>alert('";
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