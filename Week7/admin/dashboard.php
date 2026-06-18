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

if($_SESSION['role'] != 'admin'){
    header('Location: /clinic_system/login.php');
    exit();
}

// Count stats
$total_patients     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='patient'"))['total'];
$total_doctors      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM doctors"))['total'];
$total_appointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments"))['total'];
$total_pending      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments WHERE status='pending'"))['total'];

// Recent appointments
$recent = mysqli_query($conn, "SELECT a.*, u.name AS patient_name, d.name AS doctor_name
                                FROM appointments a
                                JOIN users u ON a.patient_id = u.id
                                JOIN doctors d ON a.doctor_id = d.id
                                ORDER BY a.created_at DESC LIMIT 10");

include '../includes/header.php';
?>

<div class="container">
    <h2 class="page-title">🛡️ Admin Dashboard</h2>

    <!-- Stats Cards -->
    <div style="display:flex; gap:16px; flex-wrap:wrap; margin-bottom:28px;">

        <div class="card" style="flex:1; min-width:180px; text-align:center; border-top:4px solid #1F4E79;">
            <p style="font-size:40px; font-weight:bold; color:#1F4E79;"><?php echo $total_patients; ?></p>
            <p style="color:#666;">Total Patients</p>
        </div>

        <div class="card" style="flex:1; min-width:180px; text-align:center; border-top:4px solid #27ae60;">
            <p style="font-size:40px; font-weight:bold; color:#27ae60;"><?php echo $total_doctors; ?></p>
            <p style="color:#666;">Total Doctors</p>
        </div>

        <div class="card" style="flex:1; min-width:180px; text-align:center; border-top:4px solid #2E75B6;">
            <p style="font-size:40px; font-weight:bold; color:#2E75B6;"><?php echo $total_appointments; ?></p>
            <p style="color:#666;">Total Appointments</p>
        </div>

        <div class="card" style="flex:1; min-width:180px; text-align:center; border-top:4px solid #e67e22;">
            <p style="font-size:40px; font-weight:bold; color:#e67e22;"><?php echo $total_pending; ?></p>
            <p style="color:#666;">Pending</p>
        </div>

    </div>

    <!-- Quick Links -->
    <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:28px;">
        <a href="manage_users.php"   class="btn btn-primary">👥 Manage Patients</a>
        <a href="manage_doctors.php" class="btn btn-success">👨‍⚕️ Manage Doctors</a>
        <a href="/clinic_system/logout.php" class="btn btn-danger">🚪 Logout</a>
    </div>

    <!-- Recent Appointments -->
    <div class="card">
        <h3 style="color:#1F4E79; margin-bottom:16px;">📋 Recent Appointments</h3>

        <?php if(mysqli_num_rows($recent) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1; while($row = mysqli_fetch_assoc($recent)): ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo $row['patient_name']; ?></td>
                    <td><?php echo $row['doctor_name']; ?></td>
                    <td><?php echo date('d M Y', strtotime($row['appt_date'])); ?></td>
                    <td><?php echo date('h:i A', strtotime($row['appt_time'])); ?></td>
                    <td><?php echo $row['reason']; ?></td>
                    <td>
                        <?php
                        $status = $row['status'];
                        $color  = $status == 'confirmed' ? 'green' : ($status == 'cancelled' ? 'red' : 'orange');
                        echo "<span style='color:$color; font-weight:bold;'>".ucfirst($status)."</span>";
                        ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div style="text-align:center; padding:40px; color:#999;">
            <p style="font-size:40px;">📭</p>
            <p>No appointments yet.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>