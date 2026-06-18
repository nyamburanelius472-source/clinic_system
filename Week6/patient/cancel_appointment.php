<?php
session_start();

$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'clinic_db';
$conn   = mysqli_connect($host, $user, $pass, $dbname);

if(!isset($_SESSION['username'])){
    header('Location: /clinic_system/login.php');
    exit();
}

$id         = $_GET['id'];
$patient_id = $_SESSION['user_id'];

$sql = "UPDATE appointments SET status='cancelled' 
        WHERE id='$id' AND patient_id='$patient_id'";
mysqli_query($conn, $sql);

header('Location: dashboard.php');
exit();
?>
