<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Solar CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        /* Minimal custom styles using Bootstrap variables */
        :root {
            --bs-primary: #A945BB;
            --bs-primary-rgb: 169, 69, 187;
            --bs-warning: #FFD30F;
            --bs-dark: #393939;
        }
        
        .btn-primary {
            background-color: #A945BB;
            border-color: #A945BB;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: #8a37a0;
            border-color: #8a37a0;
        }
        
        .bg-primary {
            background-color: #A945BB !important;
        }
        
        .bg-warning {
            background-color: #FFD30F !important;
        }
        
        .text-primary {
            color: #A945BB !important;
        }
        
        /* Remove the gradient overlay */
    </style>
</head>
<body style="background: url('https://cdnjs.cloudflare.com/ajax/libs/simple-backgrounds/1.0.0/subtle-patterns/subtle-pattern-10.jpg') repeat; background-size: 200px;">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100 py-5">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-lg border-0 rounded-4" style="backdrop-filter: blur(10px); background-color: rgba(255, 255, 255, 0.95);">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="bg-warning rounded-circle d-inline-flex p-3 mb-3">
                            <i class="fas fa-solar-panel fa-2x text-white"></i>
                        </div>
                        <h3 class="fw-bold">Welcome to Solar CMS</h3>
                        <p class="text-muted">Please login to continue</p>
                    </div>
                    
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="Enter your email address" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="Enter your password" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold">Role</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-user-tag text-muted"></i>
                                </span>
                                <select class="form-select border-start-0" id="role" name="role" required>
                                    <option value="" selected disabled>Select your role</option>
                                    <option value="ChannelPartner">Channel Partner</option>
                                    <option value="MasterDistributor">Master Distributor</option>
                                    <option value="Distributor">Distributor</option>
                                    <option value="Retailer">Retailer</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold rounded-3 py-2" id="loginBtn">
                                <span class="d-flex align-items-center justify-content-center">
                                    <span>Login</span>
                                    <i class="fas fa-sign-in-alt ms-2"></i>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Colorful Toast Notifications - Top Left -->
<div class="position-fixed top-0 start-0 p-3" style="z-index: 1100">
    <div id="toastMessage" class="toast border-0 rounded-3 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header border-0">
            <i id="toastIcon" class="me-2"></i>
            <strong class="me-auto" id="toastTitle">Notification</strong>
            <small>just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastText">
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const role = document.getElementById('role').value;

        // Add loading state to button
        const loginBtn = document.getElementById('loginBtn');
        const originalButtonContent = loginBtn.innerHTML;
        loginBtn.innerHTML = `
            <span class="d-flex align-items-center justify-content-center">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                <span>Logging in...</span>
            </span>
        `;
        loginBtn.disabled = true;

        const data = { email, password, role };

        axios.post('https://backend.evargo.solarportal.in/evargo/api/v1/auth/login', data, {
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => {
            if (response.data.status === 'success') {
                sessionStorage.setItem('access_token', response.data.access_token);
                sessionStorage.setItem('role', role);

                // Send data to PHP for session storage
                return fetch('store_session.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        access_token: response.data.access_token,
                        role: role
                    })
                });
            } else {
                throw new Error(response.data.message);
            }
        })
        .then(() => {
            // Show success toast
            showToast('Login successful! Redirecting...', 'success');

            setTimeout(() => { window.location.href = 'dashboard.php'; }, 2000);
        })
        .catch(error => {
            // Reset button state
            loginBtn.innerHTML = originalButtonContent;
            loginBtn.disabled = false;
            
            showToast(error.response?.data?.message || 'Something went wrong. Please try again.', 'error');
        });
    });

    function showToast(message, type) {
        const toastMessage = document.getElementById('toastMessage');
        const toastText = document.getElementById('toastText');
        const toastIcon = document.getElementById('toastIcon');
        const toastTitle = document.getElementById('toastTitle');
        const toastHeader = document.querySelector('.toast-header');

        // Reset classes
        toastMessage.className = 'toast border-0 rounded-3 shadow-lg';
        toastHeader.className = 'toast-header border-0';
        toastTitle.className = 'me-auto';
        
        if (type === 'success') {
            // Green background for success
            toastMessage.classList.add('bg-success', 'text-white');
            toastHeader.classList.add('bg-success', 'text-white');
            toastIcon.className = 'fas fa-check-circle text-white me-2';
            toastTitle.textContent = 'Success';
            // Change close button to white
            document.querySelector('.btn-close').classList.add('btn-close-white');
        } else {
            // Red background for error
            toastMessage.classList.add('bg-danger', 'text-white');
            toastHeader.classList.add('bg-danger', 'text-white');
            toastIcon.className = 'fas fa-exclamation-circle text-white me-2';
            toastTitle.textContent = 'Error';
            // Change close button to white
            document.querySelector('.btn-close').classList.add('btn-close-white');
        }
        
        toastText.textContent = message;

        const toast = new bootstrap.Toast(toastMessage, {
            autohide: true,
            delay: 4000
        });
        toast.show();
    }
</script>

</body>
</html>