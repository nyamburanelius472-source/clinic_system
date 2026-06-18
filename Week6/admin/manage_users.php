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

// Create new user
$cu_error   = '';
$cu_success = '';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_user'])){
    $cu_name     = trim($_POST['cu_name']);
    $cu_email    = trim($_POST['cu_email']);
    $cu_phone    = trim($_POST['cu_phone']);
    $cu_role     = $_POST['cu_role'];
    $cu_password = password_hash($_POST['cu_password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$cu_email'");
    if(mysqli_num_rows($check) > 0){
        $cu_error = 'Email already exists.';
    } else {
        $sql = "INSERT INTO users (name, email, password, phone, role)
                VALUES ('$cu_name','$cu_email','$cu_password','$cu_phone','$cu_role')";
        if(mysqli_query($conn, $sql)){
            $cu_success = 'User created successfully!';
        } else {
            $cu_error = 'Error: ' . mysqli_error($conn);
        }
    }
}

$users = mysqli_query($conn, "SELECT * FROM users WHERE role='patient' ORDER BY name");

include '../includes/header.php';
?>

<div class="container">
    <h2 class="page-title">👥 Manage Patients</h2>

    <!-- Create User Form -->
    <div class="card" style="margin-bottom:28px;">
        <h3 style="color:#0D7377; margin-bottom:16px;">➕ Create New User</h3>

        <?php if($cu_error): ?>
            <div class="alert alert-error"><?php echo $cu_error; ?></div>
        <?php endif; ?>
        <?php if($cu_success): ?>
            <div class="alert alert-success"><?php echo $cu_success; ?></div>
        <?php endif; ?>

        <form method="POST" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
            <div class="form-group" style="flex:1; min-width:160px;">
                <label>Full Name</label>
                <input type="text" name="cu_name" placeholder="Full name">
            </div>
            <div class="form-group" style="flex:1; min-width:160px;">
                <label>Email</label>
                <input type="email" name="cu_email" placeholder="Email address">
            </div>
            <div class="form-group" style="flex:1; min-width:130px;">
                <label>Phone</label>
                <input type="text" name="cu_phone" placeholder="Phone">
            </div>
            <div class="form-group" style="flex:1; min-width:130px;">
                <label>Password</label>
                <input type="password" name="cu_password" placeholder="Password">
            </div>
            <div class="form-group" style="flex:1; min-width:120px;">
                <label>Role</label>
                <select name="cu_role">
                    <option value="patient">Patient</option>
                    <option value="doctor">Doctor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="submit" name="create_user" class="btn btn-primary">Create User</button>
            </div>
        </form>
    </div>

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