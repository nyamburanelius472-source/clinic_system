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

$error   = '';
$success = '';

// Add doctor
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name  = trim($_POST['name']);
    $spec  = trim($_POST['specialization']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if(empty($name) || empty($spec) || empty($email)){
        $error = 'Name, specialization and email are required.';
    } else {
        $sql = "INSERT INTO doctors (name, specialization, email, phone)
                VALUES ('$name', '$spec', '$email', '$phone')";
        if(mysqli_query($conn, $sql)){
            $success = 'Doctor added successfully!';
        } else {
            $error = 'Error: ' . mysqli_error($conn);
        }
    }
}

// Delete doctor
if(isset($_GET['delete'])){
    $del_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM doctors WHERE id='$del_id'");
    header('Location: manage_doctors.php');
    exit();
}

$doctors = mysqli_query($conn, "SELECT * FROM doctors ORDER BY name");

include '../includes/header.php';
?>

<div class="container">
    <h2 class="page-title">👨‍⚕️ Manage Doctors</h2>

    <div style="margin-bottom:16px;">
        <a href="dashboard.php" class="btn btn-primary">← Back to Dashboard</a>
    </div>

    <!-- Add Doctor Form -->
    <div class="card" style="margin-bottom:24px;">
        <h3 style="color:#1F4E79; margin-bottom:16px;">➕ Add New Doctor</h3>

        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" style="display:flex; gap:12px; flex-wrap:wrap;">
            <div class="form-group" style="flex:1; min-width:180px;">
                <label>Doctor Name</label>
                <input type="text" name="name" placeholder="e.g. Dr. John Doe">
            </div>
            <div class="form-group" style="flex:1; min-width:180px;">
                <label>Specialization</label>
                <input type="text" name="specialization" placeholder="e.g. General Practitioner">
            </div>
            <div class="form-group" style="flex:1; min-width:180px;">
                <label>Email</label>
                <input type="email" name="email" placeholder="doctor@clinic.com">
            </div>
            <div class="form-group" style="flex:1; min-width:180px;">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="0700000000">
            </div>
            <div class="form-group" style="flex:1; min-width:100px; display:flex; align-items:flex-end;">
                <button type="submit" class="btn btn-primary" style="width:100%;">Add Doctor</button>
            </div>
        </form>
    </div>

    <!-- Doctors Table -->
    <div class="card">
        <h3 style="color:#1F4E79; margin-bottom:16px;">📋 All Doctors</h3>
        <?php if(mysqli_num_rows($doctors) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1; while($row = mysqli_fetch_assoc($doctors)): ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['specialization']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td>
                        <a href="manage_doctors.php?delete=<?php echo $row['id']; ?>"
                           class="btn btn-danger"
                           style="padding:6px 12px; font-size:13px;"
                           onclick="return confirm('Delete this doctor?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div style="text-align:center; padding:40px; color:#999;">
            <p>No doctors added yet.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>