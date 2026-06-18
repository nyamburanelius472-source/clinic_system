<?php
session_start();

$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'clinic_db';
$conn   = mysqli_connect($host, $user, $pass, $dbname);

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)){
        $error = 'All fields are required.';
    } else {
        $sql    = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 1){
            $user = mysqli_fetch_assoc($result);
            if(password_verify($password, $user['password'])){
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['name'];
                $_SESSION['role']     = $user['role'];
                if($user['role'] == 'admin'){
                    header('Location: admin/dashboard.php');
                } elseif($user['role'] == 'doctor'){
                    header('Location: doctor/dashboard.php');
                } else {
                    header('Location: patient/dashboard.php');
                }
                exit();
            } else {
                $error = 'Incorrect password. Please try again.';
            }
        } else {
            $error = 'No account found with that email.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Ladasha Clinic Booking System</title>
    <link rel="stylesheet" href="/clinic_system/css/style.css?v=10">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-box">
        <div class="auth-logo">
            <div class="auth-logo-icon">🏥</div>
        </div>
        <p style="text-align:center; color:#0D7377; font-size:13px; margin:0 0 4px;">Welcome Back</p>
        <h2>Ladasha Clinic</h2>

        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validateLogin()">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="email" placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; padding:12px; font-size:16px;">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<script src="/clinic_system/js/validate.js"></script>
</body>
</html>