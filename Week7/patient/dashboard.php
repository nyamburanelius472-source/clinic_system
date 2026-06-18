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
if($_SESSION['role'] != 'patient'){
    header('Location: /clinic_system/login.php');
    exit();
}

$patient_id = $_SESSION['user_id'];

// Stats
$total     = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE patient_id='$patient_id'"));
$pending   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE patient_id='$patient_id' AND status='pending'"));
$confirmed = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE patient_id='$patient_id' AND status='confirmed'"));

// Appointments
$sql    = "SELECT a.*, d.name AS doctor_name, d.specialization
           FROM appointments a
           JOIN doctors d ON a.doctor_id = d.id
           WHERE a.patient_id = '$patient_id'
           ORDER BY a.appt_date DESC";
$result = mysqli_query($conn, $sql);

include '../includes/header.php';
?>

<div class="container">

    <!-- Welcome Banner -->
    <div style="background:linear-gradient(135deg,#0D7377,#14A085); border-radius:12px; padding:24px 28px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <p style="color:#B2DFDB; font-size:13px; margin:0 0 4px;">Hello,</p>
            <h2 style="color:#fff; font-size:24px; margin:0 0 4px;"><?php echo $_SESSION['username']; ?> 👋</h2>
            <p style="color:#B2DFDB; font-size:13px; margin:0;">Manage your appointments below.</p>
        </div>
        <div style="background:rgba(255,255,255,0.15); width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:28px;">👤</div>
    </div>

    <!-- Stats Row -->
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px;">
        <div class="stat-card">
            <p class="stat-number"><?php echo $total; ?></p>
            <p class="stat-label">Total Appointments</p>
        </div>
        <div class="stat-card" style="border-top-color:orange;">
            <p class="stat-number" style="color:orange;"><?php echo $pending; ?></p>
            <p class="stat-label">Pending</p>
        </div>
        <div class="stat-card" style="border-top-color:#1e8449;">
            <p class="stat-number" style="color:#1e8449;"><?php echo $confirmed; ?></p>
            <p class="stat-label">Confirmed</p>
        </div>
    </div>

    <!-- Action Button -->
    <div style="margin-bottom:24px;">
        <a href="book_appointment.php" class="btn btn-primary" style="padding:12px 28px; font-size:15px;">📅 Book New Appointment</a>
        <a href="/clinic_system/logout.php" class="btn btn-danger" style="padding:12px 28px; font-size:15px; margin-left:10px;">🚪 Logout</a>
    </div>

    <!-- Appointments Table -->
    <div class="card">
        <h3 style="color:#0D7377; margin-bottom:16px;">📋 My Appointments</h3>

        <?php if(mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1; while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo $row['doctor_name']; ?></td>
                    <td><?php echo $row['specialization']; ?></td>
                    <td><?php echo date('d M Y', strtotime($row['appt_date'])); ?></td>
                    <td><?php echo date('h:i A', strtotime($row['appt_time'])); ?></td>
                    <td><?php echo $row['reason']; ?></td>
                    <td>
                        <?php
                        $status = $row['status'];
                        $color  = $status == 'confirmed' ? '#1e8449' : ($status == 'cancelled' ? '#c0392b' : 'orange');
                        echo "<span style='color:$color; font-weight:bold; background:" . ($status == 'confirmed' ? '#E8F5E9' : ($status == 'cancelled' ? '#FDEDEC' : '#FFF3E0')) . "; padding:4px 10px; border-radius:12px; font-size:13px;'>".ucfirst($status)."</span>";
                        ?>
                    </td>
                    <td>
                        <?php if($row['status'] == 'pending'): ?>
                        <a href="cancel_appointment.php?id=<?php echo $row['id']; ?>"
                           class="btn btn-danger"
                           style="padding:5px 12px; font-size:12px;"
                           onclick="return confirm('Cancel this appointment?')">Cancel</a>
                        <?php else: ?>
                        <span style="color:#999; font-size:12px;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div style="text-align:center; padding:50px 20px; color:#999;">
                <div style="font-size:60px; margin-bottom:16px;">📭</div>
                <p style="font-size:16px; margin-bottom:16px;">You have no appointments yet.</p>
                <a href="book_appointment.php" class="btn btn-primary">Book Your First Appointment</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>