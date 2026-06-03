<?php
session_start();

$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'clinic_db';
$conn   = mysqli_connect($host, $user, $pass, $dbname);

$error   = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if(empty($name) || empty($email) || empty($phone) || empty($password)){
        $error = 'All fields are required.';
    } elseif($password !== $confirm){
        $error = 'Passwords do not match.';
    } elseif(strlen($password) < 6){
        $error = 'Password must be at least 6 characters.';
    } else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if(mysqli_num_rows($check) > 0){
            $error = 'Email already registered. Please login.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password, phone, role)
                    VALUES ('$name', '$email', '$hashed', '$phone', 'patient')";
            if(mysqli_query($conn, $sql)){
                $success = 'Account created successfully! You can now login.';
            } else {
                $error = 'Something went wrong: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Ladasha Clinic Booking System</title>
    <link rel="stylesheet" href="/clinic_system/css/style.css?v=10">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-box">
        <div class="auth-logo">
            <div class="auth-logo-icon">🏥</div>
        </div>
        <p style="text-align:center; color:#0D7377; font-size:13px; margin:0 0 4px;">Join Ladasha Clinic</p>
        <h2>Create Account</h2>

        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validateRegister()">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="email" placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" id="phone" placeholder="e.g. 0712345678">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="password"
                       placeholder="Min 6 characters"
                       oninput="checkPasswordStrength(this.value)">
                <div style="margin-top:8px; background:#E0F2F1; border-radius:5px; height:8px;">
                    <div id="strength-bar" style="height:8px; border-radius:5px; width:0%; transition:all 0.3s;"></div>
                </div>
                <small id="strength-label" style="font-weight:bold;"></small>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm" id="confirm" placeholder="Repeat your password">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; padding:12px; font-size:16px;">Create Account</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<script src="/clinic_system/js/validate.js"></script>
</body>
</html>