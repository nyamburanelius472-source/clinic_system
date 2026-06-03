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

if($_SESSION['role'] != 'doctor'){
    header('Location: /clinic_system/login.php');
    exit();
}

// Get doctor_id by matching user_id to doctors table via email
$user_id  = $_SESSION['user_id'];
$user_res = mysqli_query($conn, "SELECT email FROM users WHERE id='$user_id'");
$user_row = mysqli_fetch_assoc($user_res);
$email    = $user_row['email'];

$doc_res  = mysqli_query($conn, "SELECT * FROM doctors WHERE email='$email'");
$doctor   = mysqli_fetch_assoc($doc_res);
$doctor_id = $doctor ? $doctor['id'] : 0;

// Handle confirm/cancel
if(isset($_GET['action']) && isset($_GET['id'])){
    $action  = $_GET['action'];
    $appt_id = $_GET['id'];
    if($action == 'confirm'){
        mysqli_query($conn, "UPDATE appointments SET status='confirmed' WHERE id='$appt_id' AND doctor_id='$doctor_id'");
    } elseif($action == 'cancel'){
        mysqli_query($conn, "UPDATE appointments SET status='cancelled' WHERE id='$appt_id' AND doctor_id='$doctor_id'");
    }
    header('Location: dashboard.php');
    exit();
}

// Stats
$total     = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE doctor_id='$doctor_id'"));
$pending   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE doctor_id='$doctor_id' AND status='pending'"));
$confirmed = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE doctor_id='$doctor_id' AND status='confirmed'"));

// Fetch appointments
$result = mysqli_query($conn, "SELECT a.*, u.name AS patient_name, u.phone
                                FROM appointments a
                                JOIN users u ON a.patient_id = u.id
                                WHERE a.doctor_id = '$doctor_id'
                                ORDER BY a.appt_date ASC");

include '../includes/header.php';
?>

<div class="container">

    <!-- Welcome Banner -->
    <div style="background:linear-gradient(135deg,#0D7377,#14A085); border-radius:12px; padding:24px 28px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <p style="color:#B2DFDB; font-size:13px; margin:0 0 4px;">Doctor Panel</p>
            <h2 style="color:#fff; font-size:24px; margin:0 0 4px;">Dr. <?php echo $_SESSION['username']; ?> 👋</h2>
            <p style="color:#B2DFDB; font-size:13px; margin:0;">
                <?php echo $doctor ? $doctor['specialization'] : ''; ?>
            </p>
        </div>
        <div style="background:rgba(255,255,255,0.15); width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:28px;">👨‍⚕️</div>
    </div>

    <!-- Stats -->
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

    <!-- Appointments -->
    <div class="card">
        <h3 style="color:#0D7377; margin-bottom:16px;">📋 My Patient Appointments</h3>

        <?php if(mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Phone</th>
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
                    <td><?php echo $row['patient_name']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo date('d M Y', strtotime($row['appt_date'])); ?></td>
                    <td><?php echo date('h:i A', strtotime($row['appt_time'])); ?></td>
                    <td><?php echo $row['reason']; ?></td>
                    <td>
                        <?php
                        $status = $row['status'];
                        $color  = $status == 'confirmed' ? '#1e8449' : ($status == 'cancelled' ? '#c0392b' : 'orange');
                        $bg     = $status == 'confirmed' ? '#E8F5E9' : ($status == 'cancelled' ? '#FDEDEC' : '#FFF3E0');
                        echo "<span style='color:$color; background:$bg; padding:4px 10px; border-radius:12px; font-size:13px; font-weight:bold;'>".ucfirst($status)."</span>";
                        ?>
                    </td>
                    <td>
                        <?php if($row['status'] == 'pending'): ?>
                        <a href="dashboard.php?action=confirm&id=<?php echo $row['id']; ?>"
                           class="btn btn-success"
                           style="padding:5px 10px; font-size:12px;"
                           onclick="return confirm('Confirm this appointment?')">Confirm</a>
                        <a href="dashboard.php?action=cancel&id=<?php echo $row['id']; ?>"
                           class="btn btn-danger"
                           style="padding:5px 10px; font-size:12px; margin-left:4px;"
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
            <p style="font-size:16px;">No appointments assigned yet.</p>
        </div>
        <?php endif; ?>
    </div>

    <div style="margin-top:16px;">
        <a href="/clinic_system/logout.php" class="btn btn-danger">🚪 Logout</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>