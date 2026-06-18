<?php
session_start();
$host='localhost'; $user='root'; $pass=''; $dbname='clinic_db';
$conn = mysqli_connect($host,$user,$pass,$dbname);
$doctors = mysqli_query($conn, "SELECT * FROM doctors ORDER BY name");
include 'includes/header.php';
?>

<div class="container">
    <h2 class="page-title">👨‍⚕️ Our Doctors</h2>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px,1fr)); gap:20px;">
        <?php while($doc = mysqli_fetch_assoc($doctors)): ?>
        <div class="card" style="text-align:center; padding:28px 20px;">
            <div style="background:#E0F2F1; width:70px; height:70px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; font-size:32px;">👨‍⚕️</div>
            <h3 style="color:#0D7377; margin:0 0 6px;"><?php echo $doc['name']; ?></h3>
            <p style="color:#14A085; font-size:13px; font-weight:600; margin:0 0 8px;"><?php echo $doc['specialization']; ?></p>
            <p style="color:#666; font-size:13px; margin:0 0 4px;">📧 <?php echo $doc['email']; ?></p>
            <p style="color:#666; font-size:13px; margin:0 0 16px;">📞 <?php echo $doc['phone']; ?></p>
            <a href="register.php" class="btn btn-primary" style="width:100%; display:block; text-align:center;">Book Appointment</a>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>