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

$patient_id = $_SESSION['user_id'];
$id         = $_GET['id'];
$error      = '';
$success    = '';

$appt = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT a.*, d.name AS doctor_name 
     FROM appointments a 
     JOIN doctors d ON a.doctor_id = d.id 
     WHERE a.id='$id' AND a.patient_id='$patient_id'"));

if(!$appt){
    header('Location: dashboard.php');
    exit();
}

$doctors = mysqli_query($conn, "SELECT * FROM doctors ORDER BY name");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $doctor_id = $_POST['doctor_id'];
    $appt_date = $_POST['appt_date'];
    $appt_time = $_POST['appt_time'];
    $reason    = trim($_POST['reason']);

    if(empty($doctor_id) || empty($appt_date) || empty($appt_time) || empty($reason)){
        $error = 'All fields are required.';
    } else {
        $sql = "UPDATE appointments 
                SET doctor_id='$doctor_id', appt_date='$appt_date', 
                    appt_time='$appt_time', reason='$reason', status='pending'
                WHERE id='$id' AND patient_id='$patient_id'";
        if(mysqli_query($conn, $sql)){
            $success = 'Appointment updated successfully!';
        } else {
            $error = 'Error: ' . mysqli_error($conn);
        }
    }
}

include '../includes/header.php';
?>

<div class="container">
    <h2 class="page-title"> Edit Appointment</h2>
    <div class="card">
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
                <a href="dashboard.php" style="margin-left:12px; font-weight:bold;">Back to Dashboard →</a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Select Doctor</label>
                <select name="doctor_id">
                    <option value="">-- Choose a Doctor --</option>
                    <?php while($doc = mysqli_fetch_assoc($doctors)): ?>
                    <option value="<?php echo $doc['id']; ?>"
                        <?php echo $doc['id'] == $appt['doctor_id'] ? 'selected' : ''; ?>>
                        <?php echo $doc['name']; ?> — <?php echo $doc['specialization']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Appointment Date</label>
                <input type="date" name="appt_date" 
                       value="<?php echo $appt['appt_date']; ?>"
                       min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label>Appointment Time</label>
                <select name="appt_time">
                    <?php
                    $times = ['08:00'=>'8:00 AM','09:00'=>'9:00 AM','10:00'=>'10:00 AM',
                              '11:00'=>'11:00 AM','12:00'=>'12:00 PM','14:00'=>'2:00 PM',
                              '15:00'=>'3:00 PM','16:00'=>'4:00 PM'];
                    foreach($times as $val => $label):
                        $selected = (substr($appt['appt_time'],0,5) == $val) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $val; ?>" <?php echo $selected; ?>>
                        <?php echo $label; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Reason for Visit</label>
                <textarea name="reason" rows="4"><?php echo $appt['reason']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Appointment</button>
            <a href="dashboard.php" class="btn" style="background:#eee; color:#333; margin-left:10px;">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>