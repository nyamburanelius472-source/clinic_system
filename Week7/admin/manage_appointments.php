<?php
session_start();

$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'clinic_db';
$conn   = mysqli_connect($host, $user, $pass, $dbname);

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    header('Location: /clinic_system/login.php');
    exit();
}

// Delete appointment
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM appointments WHERE id='$id'");
    header('Location: manage_appointments.php');
    exit();
}

// Update status
if(isset($_GET['status']) && isset($_GET['id'])){
    $status = $_GET['status'];
    $id     = $_GET['id'];
    if(in_array($status, ['confirmed','cancelled','pending'])){
        mysqli_query($conn, "UPDATE appointments SET status='$status' WHERE id='$id'");
    }
    header('Location: manage_appointments.php');
    exit();
}

$appointments = mysqli_query($conn, "SELECT a.*, u.name AS patient_name, d.name AS doctor_name 
                                      FROM appointments a 
                                      JOIN users u ON a.patient_id = u.id 
                                      JOIN doctors d ON a.doctor_id = d.id 
                                      ORDER BY a.appt_date DESC");

include '../includes/header.php';
?>

<div class="container">
    <h2 class="page-title">📋 All Appointments</h2>

    <div style="margin-bottom:20px;">
        <a href="dashboard.php" class="btn btn-primary">← Back to Dashboard</a>
    </div>

    <div class="card">
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1; while($row = mysqli_fetch_assoc($appointments)): ?>
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
                        $color  = $status == 'confirmed' ? '#1e8449' : ($status == 'cancelled' ? '#c0392b' : 'orange');
                        $bg     = $status == 'confirmed' ? '#E8F5E9' : ($status == 'cancelled' ? '#FDEDEC' : '#FFF3E0');
                        echo "<span style='color:$color; background:$bg; padding:4px 10px; border-radius:12px; font-size:13px; font-weight:bold;'>".ucfirst($status)."</span>";
                        ?>
                    </td>
                    <td style="display:flex; gap:4px;">
                        <?php if($row['status'] != 'confirmed'): ?>
                        <a href="manage_appointments.php?status=confirmed&id=<?php echo $row['id']; ?>"
                           class="btn btn-success"
                           style="padding:4px 8px; font-size:11px;"
                           onclick="return confirm('Confirm this appointment?')">Confirm</a>
                        <?php endif; ?>
                        <?php if($row['status'] != 'cancelled'): ?>
                        <a href="manage_appointments.php?status=cancelled&id=<?php echo $row['id']; ?>"
                           class="btn btn-danger"
                           style="padding:4px 8px; font-size:11px;"
                           onclick="return confirm('Cancel?')">Cancel</a>
                        <?php endif; ?>
                        <a href="manage_appointments.php?delete=<?php echo $row['id']; ?>"
                           class="btn btn-danger"
                           style="padding:4px 8px; font-size:11px; background:#7b0000;"
                           onclick="return confirm('Permanently delete?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>