<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ladasha Clinic Booking System</title>
    <link rel="stylesheet" href="/clinic_system/css/style.css?v=10">
</head>
<body>

<nav>
    <a class="logo" href="/clinic_system/index.php">
        <div class="logo-icon">🏥</div>
        <span class="logo-text">Ladasha</span>
    </a>
    <ul>
        <?php if(isset($_SESSION['username'])): ?>
            <li><span>Welcome, <?php echo $_SESSION['username']; ?></span></li>

            <?php if($_SESSION['role'] == 'patient'): ?>
                <li><a href="/clinic_system/patient/dashboard.php">Dashboard</a></li>
                <li><a href="/clinic_system/patient/book_appointment.php">Book Appointment</a></li>
            <?php elseif($_SESSION['role'] == 'doctor'): ?>
                <li><a href="/clinic_system/doctor/dashboard.php">Dashboard</a></li>
            <?php elseif($_SESSION['role'] == 'admin'): ?>
                <li><a href="/clinic_system/admin/dashboard.php">Dashboard</a></li>
                <li><a href="/clinic_system/admin/manage_users.php">Users</a></li>
                <li><a href="/clinic_system/admin/manage_doctors.php">Doctors</a></li>
            <?php endif; ?>

            <li><a href="/clinic_system/logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="/clinic_system/index.php">Home</a></li>
            <li><a href="/clinic_system/login.php">Login</a></li>
            <li><a href="/clinic_system/register.php" class="btn-nav">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>