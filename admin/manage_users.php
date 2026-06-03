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

// Delete user
if(isset($_GET['delete'])){
    $del_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id='$del_id' AND role='patient'");
    header('Location: manage_users.php');
    exit();
}

$users = mysqli_query($conn, "SELECT * FROM users WHERE role='patient' ORDER BY name");

include '../includes/header.php';
?>

<div class="container">
    <h2 class="page-title">👥 Manage Patients</h2>

    <div style="margin-bottom:16px;">
        <a href="dashboard.php" class="btn btn-primary">← Back to Dashboard</a>
    </div>

    <div class="card">
        <?php if(mysqli_num_rows($users) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Registered</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1; while($row = mysqli_fetch_assoc($users)): ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <a href="manage_users.php?delete=<?php echo $row['id']; ?>"
                           class="btn btn-danger"
                           style="padding:6px 12px; font-size:13px;"
                           onclick="return confirm('Delete this patient?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div style="text-align:center; padding:40px; color:#999;">
            <p>No patients registered yet.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>