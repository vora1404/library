<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<?php
require_once "connect.php";

$session_duration = 3600; // 1 hour in seconds
ini_set('session.cookie_lifetime', $session_duration);
ini_set('session.gc_maxlifetime', $session_duration);
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    date_default_timezone_set("Asia/Bangkok");

    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = $_POST["password"];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $datetime = date('Y-m-d H:i:s');

    $sql = "SELECT * FROM users u WHERE username = '$username' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = $row["password"];

        echo "สวัสดี" ;
        echo " " ;
        
        if (password_verify($password, $storedPassword)) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["user_role"] = $row["level"];
            $name = $row['name'];
            // No need to close MySQL connection here
            ?>
            <script>
                Swal.fire({
                    title: 'สวัสดีคุณ <?php echo $name; ?>!',
                    html: '<div>เข้าสู่ระบบสำเร็จ!</div>',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 5000
                }).then(function () {
                    window.location.href = 'admin_booking.php';
                });
            </script>
            <?php
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Invalid username";
    }
}
?>

<body>
    <div class="container">
        <h2 class="mt-5 mb-3">Login</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" placeholder='' required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder='' required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
            <div class="text-danger mt-2"><?php echo $error; ?></div>
        </form>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
