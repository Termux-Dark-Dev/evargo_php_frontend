<?php
session_start();
if (!isset($_SESSION['access_token']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Agent | EVargo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #A945BB;
            --secondary-color: #933ba6;
            --accent-color: #FFD30F;
            --dark-color: #393939;
            --light-color: #ffffff;
            --light-bg: #f8f5fa;
            --primary-gradient: linear-gradient(135deg, #A945BB, #8C38A3);
            --card-shadow: 0 8px 30px rgba(169, 69, 187, 0.1);
            --border-radius: 12px;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .page-container {
            padding: 2.5rem 1rem;
        }
        
        .form-container {
            background: var(--light-color);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 2.5rem;
            margin-bottom: 2rem;
            border-top: 5px solid var(--primary-color);
        }
        
        .page-title {
            margin-bottom: 2rem;
            color: var(--dark-color);
            font-weight: 700;
            position: relative;
            padding-bottom: 0.75rem;
            display: inline-block;
        }
        
        .page-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 4px;
            width: 80px;
            background: var(--primary-color);
            border-radius: 2px;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }
        
        .form-control, .form-select {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #e0d5e5;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(169, 69, 187, 0.25);
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 0.85rem 2.5rem;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(169, 69, 187, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(169, 69, 187, 0.3);
        }
        
        .btn-outline-secondary {
            border-color: #e0d5e5;
            color: var(--dark-color);
            font-weight: 600;
            padding: 0.85rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--light-bg);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            background-color: rgba(169, 69, 187, 0.1);
            padding: 0.6rem;
            border-radius: 50%;
            margin-right: 0.8rem;
            color: var(--primary-color);
        }
        
        .section-divider {
            height: 1px;
            background-color: #e0d5e5;
            margin: 2rem 0;
        }
        
        .permissions-section {
            background-color: rgba(169, 69, 187, 0.05);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-top: 1.5rem;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }
        
        .form-text {
            color: #7c7c7c;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }
        
        .alert {
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: none;
        }
        
        .alert-success {
            background-color: rgba(255, 211, 15, 0.2);
            color: var(--dark-color);
            border-left: 4px solid var(--accent-color);
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        /* Animated Input Icons */
        .input-icon-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #7c7c7c;
            transition: all 0.3s ease;
        }
        
        .form-control:focus + .input-icon,
        .form-select:focus + .input-icon {
            color: var(--primary-color);
        }
        
        /* Page Header */
        .page-header {
            background: var(--primary-gradient);
            color: var(--light-color);
            padding: 1.5rem 0;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }
        
        .page-header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            opacity: 0.8;
            margin-bottom: 0;
        }
        
        /* Loading Spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(57, 57, 57, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s;
            backdrop-filter: blur(5px);
        }
        
        .spinner-overlay.show {
            visibility: visible;
            opacity: 1;
        }
        
        .spinner-content {
            background: var(--light-color);
            padding: 2.5rem;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        
        /* Tooltip styling */
        .tooltip-icon {
            color: #7c7c7c;
            cursor: pointer;
            margin-left: 0.5rem;
            font-size: 0.9rem;
        }
        
        /* Radio cards for role selection */
        .role-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .role-card {
            flex: 1;
            min-width: 200px;
            border: 2px solid #e0d5e5;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .role-card.active {
            border-color: var(--primary-color);
            background-color: rgba(169, 69, 187, 0.05);
        }
        
        .role-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .role-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            opacity: 0.7;
        }
        
        .role-card.active .role-icon {
            opacity: 1;
        }
        
        .role-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .role-description {
            font-size: 0.85rem;
            color: var(--dark-color);
        }
        
        /* Permission input styling */
        .permission-input {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .permission-input label {
            flex: 2;
            margin-bottom: 0;
        }
        
        .permission-input .input-group {
            flex: 1;
            max-width: 150px;
        }
        
        .permission-input .btn-outline-secondary {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .permission-input .btn-outline-secondary:hover {
            background-color: var(--primary-color);
            color: var(--light-color);
        }
        
        /* Success Checkmark Animation */
        @keyframes checkmark {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .success-checkmark {
            width: 90px;
            height: 90px;
            margin: 0 auto;
            background-color: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: checkmark 0.5s ease-in-out;
            box-shadow: 0 10px 25px rgba(255, 211, 15, 0.4);
        }
        
        .success-checkmark i {
            color: var(--dark-color);
            font-size: 3rem;
        }
        
        /* Modal customization for success dialog */
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }
        
        .modal .btn-primary {
            padding-left: 2rem;
            padding-right: 2rem;
        }
        
        /* Custom styles for buttons and accents */
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .btn-outline-secondary .bi-plus,
        .btn-outline-secondary .bi-dash {
            font-weight: bold;
        }
        
        .spinner-border.text-primary {
            color: var(--primary-color) !important;
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>
    
    <!-- Page Content -->
    <div class="container page-container">
        <!-- Page Header -->
        <!-- <div class="page-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="d-flex justify-content-center align-items-center" 
                             style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%;">
                            <i class="bi bi-person-plus" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h1>Create Agent</h1>
                        <p>Add a new agent to your network with role-based permissions</p>
                    </div>
                </div>
            </div>
        </div> -->
        
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <!-- Alert Placeholder for Messages -->
                <div id="alertPlaceholder"></div>
                
                <div class="form-container">
                    <form id="createAgentForm">
                        <!-- Basic Information Section -->
                        <div class="mb-4">
                            <div class="section-title">
                                <i class="bi bi-person-badge"></i>Basic Information
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <div class="input-icon-wrapper">
                                        <input type="text" class="form-control" id="name" name="name" 
                                               placeholder="Enter agent's full name" required>
                                        <i class="bi bi-person input-icon"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-icon-wrapper">
                                        <input type="email" class="form-control" id="email" name="email" 
                                               placeholder="Enter email address" required>
                                        <i class="bi bi-envelope input-icon"></i>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <div class="input-icon-wrapper">
                                        <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                               placeholder="10-digit number" pattern="[0-9]{10}" required>
                                        <i class="bi bi-phone input-icon"></i>
                                    </div>
                                    <div class="form-text">Enter 10-digit number without country code</div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-icon-wrapper">
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Create password" required>
                                        <i class="bi bi-lock input-icon"></i>
                                    </div>
                                    <div class="form-text">Password for the agent's account</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <div class="input-icon-wrapper">
                                        <input type="password" class="form-control" id="confirm_password" 
                                               placeholder="Confirm password" required>
                                        <i class="bi bi-shield-lock input-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="section-divider"></div>
                        
                        <!-- Role Selection -->
                        <div class="mb-4">
                            <div class="section-title">
                                <i class="bi bi-diagram-3"></i>Role Selection
                            </div>
                            
                            <p class="mb-3">Select the role you want to assign to this agent:</p>
                            
                            <div class="role-cards" id="roleCards">
                                <!-- Role cards will be dynamically populated -->
                            </div>
                            
                            <!-- Hidden input for role (not using radio button) -->
                            <div style="display: none;">
                                <input type="hidden" name="role" id="roleInput" value="">
                            </div>
                        </div>
                        
                        <!-- Creation Permissions (will be shown/hidden based on role) -->
                        <div class="permissions-section" id="permissionsSection" style="display: none;">
                            <div class="section-title" style="margin-top: 0;">
                                <i class="bi bi-shield-check"></i>Creation Permissions
                            </div>
                            
                            <p class="mb-4">Define how many agents this user can create:</p>
                            
                            <div id="permissionsContainer">
                                <!-- Permission fields will be dynamically populated -->
                            </div>
                        </div>
                        
                        <div class="section-divider"></div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-outline-secondary me-md-2" onclick="resetForm()">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-plus-fill me-1"></i>Create Agent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loading Spinner -->
    <div class="spinner-overlay" id="loadingSpinner">
        <div class="spinner-content">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mb-0 fw-bold">Creating agent...</p>
            <p class="text-muted small mt-2">Please wait while we process your request</p>
        </div>
    </div>

    <!-- Bootstrap & Axios JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentRole = "<?php echo $role; ?>";
        const roleCards = document.getElementById('roleCards');
        const roleInput = document.getElementById('roleInput');
        const permissionsSection = document.getElementById('permissionsSection');
        const permissionsContainer = document.getElementById('permissionsContainer');
        const createAgentForm = document.getElementById('createAgentForm');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const alertPlaceholder = document.getElementById('alertPlaceholder');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        
        // Role information with descriptions
        const roleInfo = {
            "MasterDistributor": {
                name: "Master Distributor",
                icon: "bi-building",
                description: "Can create Distributors and Retailers"
            },
            "Distributor": {
                name: "Distributor",
                icon: "bi-shop",
                description: "Can create Retailers only"
            },
            "Retailer": {
                name: "Retailer",
                icon: "bi-basket",
                description: "End user with no creation rights"
            }
        };
        
        // Define which roles can create which other roles
        const rolePermissions = {
            "ChannelPartner": ["MasterDistributor", "Distributor", "Retailer"],
            "MasterDistributor": ["Distributor", "Retailer"],
            "Distributor": ["Retailer"]
        };
        
        // Get available roles for current user
        const availableRoles = rolePermissions[currentRole] || [];
        
        // Populate role cards
        availableRoles.forEach(role => {
            const info = roleInfo[role] || { name: role, icon: "bi-person-badge", description: "" };
            
            const card = document.createElement('div');
            card.className = 'role-card';
            card.dataset.role = role;
            card.innerHTML = `
                <div class="role-icon">
                    <i class="bi ${info.icon}"></i>
                </div>
                <div class="role-name">${info.name}</div>
                <div class="role-description">${info.description}</div>
            `;
            
            card.addEventListener('click', function() {
                // Remove active class from all cards
                document.querySelectorAll('.role-card').forEach(c => c.classList.remove('active'));
                // Add active class to this card
                this.classList.add('active');
                // Set the hidden input value
                roleInput.value = this.dataset.role;
                // Update permissions fields
                updatePermissionsFields(this.dataset.role);
            });
            
            roleCards.appendChild(card);
        });
        
        // Function to update permission fields based on selected role
        function updatePermissionsFields(selectedRole) {
            // Clear existing fields
            permissionsContainer.innerHTML = '';
            
            // Hide permissions section for Retailer role
            if (selectedRole === 'Retailer') {
                permissionsSection.style.display = 'none';
                return;
            }
            
            permissionsSection.style.display = 'block';
            
            // Add Retailer creation field for all non-retailer roles
            createPermissionField('allowed_retailer_creation', 'Retailer');
            
            // Add role-specific fields
            if (selectedRole === 'MasterDistributor') {
                createPermissionField('allowed_distributor_creation', 'Distributor');
            } else if (selectedRole === 'ChannelPartner') {
                createPermissionField('allowed_master_distributor_creation', 'Master Distributor');
                createPermissionField('allowed_distributor_creation', 'Distributor');
            }
        }
        
        // Create permission input field
        function createPermissionField(fieldName, displayName) {
            const div = document.createElement('div');
            div.className = 'permission-input';
            
            div.innerHTML = `
                <label for="${fieldName}" class="form-label">
                    <i class="bi bi-people me-2 text-primary"></i>
                    Number of ${displayName}s:
                </label>
                <div class="input-group">
                    <button class="btn btn-outline-secondary" type="button" onclick="decreaseValue('${fieldName}')">
                        <i class="bi bi-dash"></i>
                    </button>
                    <input type="number" class="form-control text-center" id="${fieldName}" 
                        name="${fieldName}" min="0" value="0" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="increaseValue('${fieldName}')">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            `;
            
            permissionsContainer.appendChild(div);
        }
        
        // Validate password confirmation
        confirmPasswordInput.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
        
        // Form validation before submission
        createAgentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Check if role is selected
            if (!roleInput.value) {
                showAlert('danger', 'Please select a role for the agent.');
                return;
            }
            
            // Check if passwords match
            if (passwordInput.value !== confirmPasswordInput.value) {
                showAlert('danger', 'Passwords do not match. Please try again.');
                return;
            }
            
            // Show loading spinner
            loadingSpinner.classList.add('show');
            
            // Prepare form data
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone_number: document.getElementById('phone_number').value,
                password: passwordInput.value,
                role: roleInput.value,
                allowed_retailer_creation: parseInt(document.getElementById('allowed_retailer_creation')?.value || 0),
                allowed_master_distributor_creation: parseInt(document.getElementById('allowed_master_distributor_creation')?.value || 0),
                allowed_distributor_creation: parseInt(document.getElementById('allowed_distributor_creation')?.value || 0),
                allowed_channel_partner_creation: 0 // Not used in your example
            };
            
            // If role is Retailer, set all creation permissions to 0
            if (formData.role === 'Retailer') {
                formData.allowed_retailer_creation = 0;
                formData.allowed_master_distributor_creation = 0;
                formData.allowed_distributor_creation = 0;
                formData.allowed_channel_partner_creation = 0;
            }
            
            // Submit data to API
            axios.post('https://backend.evargo.solarportal.in/evargo/api/v1/agent/create-agent', formData, {
                headers: {
                    'Authorization': 'Bearer ' + sessionStorage.getItem('access_token'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                // Hide loading spinner
                loadingSpinner.classList.remove('show');
                
                if (response.data.status === 'success') {
                    // Reset form first
                    resetForm();
                    // Show success dialog with animation
                    showSuccessAlert('Agent created successfully!');
                } else {
                    // Show error message
                    showAlert('danger', response.data.message || 'An error occurred while creating the agent.');
                }
            })
            .catch(error => {
                // Hide loading spinner
                loadingSpinner.classList.remove('show');
                
                // Show error message
                const errorMessage = error.response?.data?.message || 'An error occurred while creating the agent.';
                showAlert('danger', errorMessage);
                console.error('Error creating agent:', error);
            });
        });
        
        // Reset form function
        window.resetForm = function() {
            createAgentForm.reset();
            permissionsSection.style.display = 'none';
            alertPlaceholder.innerHTML = '';
            
            // Reset role cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('active');
            });
            
            roleInput.value = '';
        };
        
        // Show alert function
        function showAlert(type, message) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'} me-2" 
                       style="font-size: 1.25rem;"></i>
                    <div>
                        <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            alertPlaceholder.innerHTML = '';
            alertPlaceholder.appendChild(alert);
            
            // Scroll to alert
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Show success dialog instead of toast
        function showSuccessAlert(message) {
            // Create success dialog modal
            const successModal = document.createElement('div');
            successModal.className = 'modal fade';
            successModal.id = 'successModal';
            successModal.setAttribute('tabindex', '-1');
            successModal.setAttribute('aria-labelledby', 'successModalLabel');
            successModal.setAttribute('aria-hidden', 'true');
            
            successModal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center pb-4">
                            <div class="success-checkmark mb-4">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <h4 class="mb-3">Success!</h4>
                            <p class="mb-0">${message}</p>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">Continue</button>
                        </div>
                    </div>
                </div>
            `;
            
            // Add modal to body
            document.body.appendChild(successModal);
            
            // Initialize and show the modal
            const modal = new bootstrap.Modal(successModal);
            modal.show();
            
            // Remove modal from DOM after it's hidden
            successModal.addEventListener('hidden.bs.modal', function() {
                successModal.remove();
            });
        }
        
        // Increment/decrement buttons for number inputs
        window.increaseValue = function(id) {
            const input = document.getElementById(id);
            input.value = parseInt(input.value) + 1;
        };
        
        window.decreaseValue = function(id) {
            const input = document.getElementById(id);
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
            }
        };
    });
    </script>
</body>
</html>