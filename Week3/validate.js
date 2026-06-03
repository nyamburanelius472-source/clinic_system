// ── Password Strength Checker ──────────────────────
function checkPasswordStrength(password) {
    const strengthBar   = document.getElementById('strength-bar');
    const strengthLabel = document.getElementById('strength-label');

    if(!strengthBar) return;

    let strength = 0;
    if(password.length >= 6)              strength++;
    if(password.match(/[A-Z]/))           strength++;
    if(password.match(/[0-9]/))           strength++;
    if(password.match(/[^A-Za-z0-9]/))   strength++;

    const levels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
    const colors = ['', '#e74c3c', '#e67e22', '#f1c40f', '#27ae60'];
    const widths = ['', '25%', '50%', '75%', '100%'];

    strengthBar.style.width            = widths[strength];
    strengthBar.style.backgroundColor = colors[strength];
    strengthLabel.textContent          = levels[strength];
    strengthLabel.style.color         = colors[strength];
}

// ── Register Validation ────────────────────────────
function validateRegister() {
    const name     = document.getElementById('name').value.trim();
    const email    = document.getElementById('email').value.trim();
    const phone    = document.getElementById('phone').value.trim();
    const password = document.getElementById('password').value;
    const confirm  = document.getElementById('confirm').value;

    if(name === '' || email === '' || phone === '' || password === '') {
        alert('All fields are required.');
        return false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        return false;
    }

    if(password.length < 6) {
        alert('Password must be at least 6 characters.');
        return false;
    }

    if(password !== confirm) {
        alert('Passwords do not match.');
        return false;
    }

    return true;
}

// ── Login Validation ───────────────────────────────
function validateLogin() {
    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    if(email === '' || password === '') {
        alert('All fields are required.');
        return false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        return false;
    }

    return true;
}