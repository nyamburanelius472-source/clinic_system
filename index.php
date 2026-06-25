<?php 
include 'includes/header.php';
$host = 'localhost'; $user = 'root'; $pass = ''; $dbname = 'clinic_db';
$conn = mysqli_connect($host, $user, $pass, $dbname);
?>

<!-- Hero Section -->
<div style="background:linear-gradient(135deg,#0D7377,#14A085); padding:50px 30px;">
    <div style="max-width:1100px; margin:0 auto;">
        <div style="display:flex; flex-wrap:wrap; gap:40px; align-items:center;">
            
            <!-- Left side -->
            <div style="flex:1; min-width:280px;">
                <div style="background:rgba(255,255,255,0.15); color:#fff; font-size:13px; padding:5px 14px; border-radius:20px; display:inline-block; margin-bottom:16px;">
                    ✓ Trusted Healthcare Platform
                </div>
                <h1 style="color:#fff; font-size:36px; font-weight:700; margin:0 0 12px; line-height:1.3;">
                    Ladasha Clinic<br>Booking System
                </h1>
                <p style="color:#B2DFDB; font-size:16px; margin:0 0 10px;">Better Healthcare. Better Future.</p>
                <p style="color:#B2DFDB; font-size:14px; margin:0 0 28px; line-height:1.7;">
                    Book appointments with qualified doctors online.<br>Fast, easy and 100% secure.
                </p>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <a href="register.php" style="background:#fff; color:#0D7377; font-size:15px; font-weight:700; padding:12px 24px; border-radius:8px; text-decoration:none;">Get Started</a>
                    <a href="login.php" style="border:2px solid rgba(255,255,255,0.6); color:#fff; font-size:15px; padding:12px 24px; border-radius:8px; text-decoration:none;">Login</a>
                </div>
            </div>

            <!-- Right side — Doctor cards -->
            <div style="flex:1; min-width:280px; background:rgba(255,255,255,0.1); border-radius:16px; padding:20px;">
                <p style="color:#fff; font-size:14px; font-weight:600; margin:0 0 14px;">
                    📅 Next Available Doctors
                </p>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <?php
                    $doctors = mysqli_query($conn, "SELECT * FROM doctors LIMIT 3");
                    while($doc = mysqli_fetch_assoc($doctors)):
                    ?>
                    <div style="display:flex; align-items:center; gap:12px; background:rgba(255,255,255,0.15); padding:10px 14px; border-radius:10px;">
                        <div style="background:#fff; width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:18px;">👨‍⚕️</div>
                        <div style="flex:1;">
                            <p style="font-size:13px; font-weight:600; color:#fff; margin:0;"><?php echo $doc['name']; ?></p>
                            <p style="font-size:11px; color:#B2DFDB; margin:0;"><?php echo $doc['specialization']; ?></p>
                        </div>
                        <div style="background:#fff; color:#0D7377; font-size:11px; font-weight:600; padding:4px 10px; border-radius:5px;">Available</div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <a href="doctors.php" style="display:block; text-align:center; margin-top:14px; background:rgba(255,255,255,0.2); color:#fff; font-size:13px; padding:9px; border-radius:8px; text-decoration:none; font-weight:600;">
                    View All Doctors →
                </a>
            </div>

        </div>
    </div>
</div>

<!-- Stats Bar -->
<div style="background:#fff; padding:20px 30px; box-shadow:0 2px 8px rgba(13,115,119,0.1);">
    <div style="max-width:1100px; margin:0 auto; display:flex; justify-content:space-around; align-items:center; flex-wrap:wrap; gap:16px;">
        <div style="text-align:center;">
            <p style="font-size:28px; font-weight:700; color:#0D7377; margin:0;">500+</p>
            <p style="font-size:13px; color:#666; margin:4px 0 0;">Happy Patients</p>
        </div>
        <div style="width:1px; height:40px; background:#E0F2F1;"></div>
        <div style="text-align:center;">
            <p style="font-size:28px; font-weight:700; color:#0D7377; margin:0;">4</p>
            <p style="font-size:13px; color:#666; margin:4px 0 0;">Qualified Doctors</p>
        </div>
        <div style="width:1px; height:40px; background:#E0F2F1;"></div>
        <div style="text-align:center;">
            <p style="font-size:28px; font-weight:700; color:#0D7377; margin:0;">24/7</p>
            <p style="font-size:13px; color:#666; margin:4px 0 0;">Always Available</p>
        </div>
        <div style="width:1px; height:40px; background:#E0F2F1;"></div>
        <div style="text-align:center;">
            <p style="font-size:28px; font-weight:700; color:#0D7377; margin:0;">100%</p>
            <p style="font-size:13px; color:#666; margin:4px 0 0;">Secure & Private</p>
        </div>
    </div>
</div>

<!-- Features Section -->
<div style="background:#F0FAF9; padding:40px 30px;">
    <div style="max-width:1100px; margin:0 auto;">
        <h2 style="text-align:center; color:#0D7377; font-size:26px; margin:0 0 30px;">Why Choose Ladasha?</h2>
        <div style="display:flex; gap:20px; flex-wrap:wrap; justify-content:center;">

            <div style="background:#fff; border-radius:12px; padding:28px 24px; flex:1; min-width:200px; text-align:center; border-left:4px solid #14A085; box-shadow:0 2px 8px rgba(13,115,119,0.08);">
                <div style="font-size:40px; margin-bottom:12px;">📅</div>
                <h3 style="color:#0D7377; margin-bottom:10px; font-size:18px;">Easy Booking</h3>
                <p style="color:#666; font-size:14px; line-height:1.6;">Book appointments with your preferred doctor in just a few clicks from anywhere.</p>
            </div>

            <div style="background:#fff; border-radius:12px; padding:28px 24px; flex:1; min-width:200px; text-align:center; border-left:4px solid #14A085; box-shadow:0 2px 8px rgba(13,115,119,0.08);">
                <div style="font-size:40px; margin-bottom:12px;">👨‍⚕️</div>
                <h3 style="color:#0D7377; margin-bottom:10px; font-size:18px;">Qualified Doctors</h3>
                <p style="color:#666; font-size:14px; line-height:1.6;">Choose from a list of qualified and experienced medical professionals.</p>
            </div>

            <div style="background:#fff; border-radius:12px; padding:28px 24px; flex:1; min-width:200px; text-align:center; border-left:4px solid #14A085; box-shadow:0 2px 8px rgba(13,115,119,0.08);">
                <div style="font-size:40px; margin-bottom:12px;">🔒</div>
                <h3 style="color:#0D7377; margin-bottom:10px; font-size:18px;">Secure & Private</h3>
                <p style="color:#666; font-size:14px; line-height:1.6;">Your medical information is kept safe and confidential at all times.</p>
            </div>

            <div style="background:#fff; border-radius:12px; padding:28px 24px; flex:1; min-width:200px; text-align:center; border-left:4px solid #14A085; box-shadow:0 2px 8px rgba(13,115,119,0.08);">
                <div style="font-size:40px; margin-bottom:12px;">⚡</div>
                <h3 style="color:#0D7377; margin-bottom:10px; font-size:18px;">Fast & Easy</h3>
                <p style="color:#666; font-size:14px; line-height:1.6;">Get instant confirmation for your appointments without any delays.</p>
            </div>

        </div>
    </div>
</div>

<!-- CTA Section -->
<div style="background:linear-gradient(135deg,#0D7377,#14A085); padding:50px 30px; text-align:center;">
    <h2 style="color:#fff; font-size:28px; margin:0 0 12px;">Ready to Book Your Appointment?</h2>
    <p style="color:#B2DFDB; font-size:16px; margin:0 0 24px;">Join hundreds of patients who trust Ladasha Clinic.</p>
    <a href="register.php" style="background:#fff; color:#0D7377; font-size:16px; font-weight:700; padding:14px 32px; border-radius:8px; text-decoration:none;">Create Free Account</a>
</div>

<?php include 'includes/footer.php'; ?>