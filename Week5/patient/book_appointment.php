<?php
session_start();

$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'clinic_db';
$conn   = mysqli_connect($host, $user, $pass, $dbname);

if(!$conn){
    die('Connection Failed: ' . mysqli_connect_error());
}

if(!isset($_SESSION['username'])){
    header('Location: /clinic_system/login.php');
    exit();
}

if($_SESSION['role'] != 'patient'){
    header('Location: /clinic_system/login.php');
    exit();
}

$error   = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $patient_id = $_SESSION['user_id'];
    $doctor_id  = $_POST['doctor_id'];
    $appt_date  = $_POST['appt_date'];
    $appt_time  = $_POST['appt_time'];
    $reason     = trim($_POST['reason']);

    if(empty($doctor_id) || empty($appt_date) || empty($appt_time) || empty($reason)){
        $error = 'All fields are required.';
    } else {
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appt_date, appt_time, reason)
                VALUES ('$patient_id', '$doctor_id', '$appt_date', '$appt_time', '$reason')";
        if(mysqli_query($conn, $sql)){
            $success = 'Appointment booked successfully!';
        } else {
            $error = 'Something went wrong: ' . mysqli_error($conn);
        }
    }
}

// Fetch doctors for dropdown
$doctors = mysqli_query($conn, "SELECT * FROM doctors ORDER BY name");

include '../includes/header.php';
?>

<div class="container">
    <h2 class="page-title">📅 Book Appointment</h2>

    <div class="card">

        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
                <a href="dashboard.php" style="margin-left:12px; font-weight:bold;">View My Appointments →</a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Select Doctor</label>
                <select name="doctor_id">
                    <option value="">-- Choose a Doctor --</option>
                    <?php while($doc = mysqli_fetch_assoc($doctors)): ?>
                    <option value="<?php echo $doc['id']; ?>">
                        Dr. <?php echo $doc['name']; ?> — <?php echo $doc['specialization']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Appointment Date</label>
                <input type="date" name="appt_date" min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label>Appointment Time</label>
                <select name="appt_time">
                    <option value="">-- Choose a Time --</option>
                    <option value="08:00">8:00 AM</option>
                    <option value="09:00">9:00 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="15:00">3:00 PM</option>
                    <option value="16:00">4:00 PM</option>
                </select>
            </div>

            <div class="form-group">
                <label>Reason for Visit</label>
                <textarea name="reason" rows="4" placeholder="Describe your symptoms or reason for visit..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Book Appointment</button>
            <a href="dashboard.php" class="btn" style="background:#eee; color:#333; margin-left:10px;">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>