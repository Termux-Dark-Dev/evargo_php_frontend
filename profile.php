<?php
session_start();
if (!isset($_SESSION['access_token']) || !isset($_SESSION['role'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Advance Markup</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #A945BB;         /* Main purple */
            --secondary-color: #871d9a;       /* Darker purple for hover/gradient */
            --accent-color: #FFD30F;          /* Yellow accent */
            --dark-color: #393939;            /* Dark gray */
            --light-bg: #f8f9fa;              /* Light background */
            --white-color: #ffffff;           /* White */
            --medium-text: #6c757d;           /* Medium gray text */
            --card-shadow: 0 8px 15px rgba(0, 0, 0, 0.06);
            --hover-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
            --transition-speed: 0.3s;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Poppins', sans-serif;
            color: var(--dark-color);
        }
        
        .page-container {
            max-width: 1300px;
            margin: 0 auto;
        }
        
        .profile-section {
            background-color: var(--white-color);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white-color);
            padding: 2.8rem 2.5rem;
            position: relative;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: var(--white-color);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 600;
            margin-right: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            border: 4px solid rgba(255, 255, 255, 0.9);
        }
        
        .profile-stats {
            background-color: var(--white-color);
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
            height: 100%;
            border-top: 4px solid var(--accent-color);
        }
        
        .profile-stats:hover {
            transform: translateY(-7px);
            box-shadow: var(--hover-shadow);
        }
        
        .stats-icon {
            font-size: 1.6rem;
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.4rem;
            margin-left: auto;
            margin-right: auto;
        }
        
        .card-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }
        
        .action-card {
            border-radius: 14px;
            border: none;
            box-shadow: var(--card-shadow);
            transition: all var(--transition-speed);
            height: 100%;
            border-bottom: 4px solid var(--primary-color);
        }
        
        .action-card:hover {
            transform: translateY(-7px);
            box-shadow: var(--hover-shadow);
        }
        
        .action-card .card-body {
            padding: 1.85rem;
        }
        
        .action-card .card-icon {
            width: 70px;
            height: 70px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1.4rem;
        }
        
        .personal-info {
            padding: 2.5rem;
            background-color: rgba(169, 69, 187, 0.03);
            border-radius: 0 0 16px 16px;
        }
        
        .info-item {
            margin-bottom: 1.8rem;
            padding-bottom: 1.8rem;
            border-bottom: 1px solid rgba(169, 69, 187, 0.1);
            transition: transform 0.2s ease;
        }
        
        .info-item:hover {
            transform: translateX(5px);
        }
        
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.6rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        
        .info-value {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--dark-color);
            padding: 0.6rem 1rem;
            background-color: rgba(255, 211, 15, 0.08);
            border-radius: 8px;
            display: inline-block;
        }
        
        .spinner-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 400px;
        }
        
        .badge-role {
            background-color: var(--accent-color);
            color: var(--dark-color);
            font-size: 0.9rem;
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            position: absolute;
            right: 2.5rem;
            top: 2.5rem;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .contact-section {
            background-color: var(--white-color);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            padding: 2.5rem;
            margin-top: 2rem;
            border-left: 4px solid var(--accent-color);
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.8rem;
            transition: transform var(--transition-speed);
            padding: 1rem;
            border-radius: 10px;
            background-color: rgba(169, 69, 187, 0.03);
        }
        
        .contact-item:hover {
            transform: translateX(8px);
            background-color: rgba(169, 69, 187, 0.07);
        }
        
        .contact-item:last-child {
            margin-bottom: 0;
        }
        
        .contact-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background-color: var(--primary-color);
            color: var(--white-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            font-size: 1.3rem;
        }
        
        .contact-info {
            flex: 1;
        }
        
        .contact-info .contact-label {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.4rem;
            color: var(--dark-color);
        }
        
        .contact-info .contact-value {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .footer {
            text-align: center;
            padding: 2.5rem 0;
            color: var(--medium-text);
            font-size: 0.95rem;
        }
        
        .version {
            display: inline-block;
            background-color: var(--accent-color);
            color: var(--dark-color);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            margin-left: 0.5rem;
            font-weight: 600;
        }
        
        .section-title {
            position: relative;
            font-weight: 700;
            margin-bottom: 2rem;
            padding-bottom: 0.9rem;
            color: var(--dark-color);
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 4px;
            width: 60px;
            background-color: var(--accent-color);
            border-radius: 10px;
        }
        
        .stats-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.7rem;
            line-height: 1;
            color: var(--primary-color);
        }
        
        .stats-label {
            color: var(--dark-color);
            font-size: 1rem;
            font-weight: 500;
        }
        
        .alert-custom {
            border-radius: 14px;
            padding: 1.8rem;
            display: flex;
            align-items: center;
            background-color: rgba(169, 69, 187, 0.1);
            border-left: 5px solid var(--primary-color);
            color: var(--dark-color);
        }
        
        .alert-custom i {
            font-size: 1.8rem;
            margin-right: 1.2rem;
            color: var(--primary-color);
        }
        
        .primary-subtle {
            background-color: rgba(169, 69, 187, 0.15);
            color: var(--primary-color);
        }
        
        .success-subtle {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }
        
        .danger-subtle {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }
        
        .warning-subtle {
            background-color: rgba(255, 211, 15, 0.2);
            color: #d6ae00;
        }
        
        .info-subtle {
            background-color: rgba(23, 162, 184, 0.15);
            color: #17a2b8;
        }
        
        @media (max-width: 767.98px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
                padding: 2.2rem 1.5rem;
            }
            
            .profile-avatar {
                margin-right: 0;
                margin-bottom: 1.8rem;
                margin-left: auto;
                margin-right: auto;
            }
            
            .badge-role {
                position: static;
                margin-top: 1.2rem;
                display: inline-block;
            }
            
            .profile-stats, .action-card {
                margin-bottom: 1.2rem;
            }
            
            .personal-info {
                padding: 1.8rem;
            }
            
            .contact-section {
                padding: 1.8rem;
            }
        }
        
        @media (max-width: 575.98px) {
            .contact-item {
                flex-direction: column;
                text-align: center;
            }
            
            .contact-icon {
                margin-right: 0;
                margin-bottom: 1.2rem;
            }
            
            .section-title::after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .section-title {
                text-align: center;
                display: block;
            }
            
            .info-item {
                text-align: center;
            }
            
            .info-value {
                display: block;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container page-container py-5">
        <div id="profile-container">
            <!-- Loading spinner -->
            <div class="spinner-container" id="loading">
                <div class="spinner-border" style="width: 3rem; height: 3rem; color: var(--primary-color);" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            
            <!-- Profile content will be loaded here -->
            <div id="profile-content" style="display: none;">
                <!-- Content will be inserted dynamically -->
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Powered by <strong style="color: var(--primary-color);">Advance Markup</strong><span class="version">v1.0.0+5</span></p>
        </div>
    </div>

    <!-- Templates for different roles -->
    <template id="retailer-template">
        <div class="profile-section">
            <div class="profile-header d-flex align-items-center">
                <div class="profile-avatar">
                    <span id="avatar-initial"></span>
                </div>
                <div>
                    <h2 id="profile-name" class="fw-bold mb-1"></h2>
                    <p class="mb-0 opacity-75" id="profile-email"></p>
                </div>
                <span class="badge-role" id="profile-role"></span>
            </div>
            
            <div class="row g-4 p-4">
                <div class="col-md-4">
                    <div class="profile-stats text-center">
                        <div class="stats-icon primary-subtle">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div class="stats-value" id="total-projects">0</div>
                        <div class="stats-label">Total Projects</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile-stats text-center">
                        <div class="stats-icon success-subtle">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-value text-success" id="approved-projects">0</div>
                        <div class="stats-label">Approved Projects</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile-stats text-center">
                        <div class="stats-icon danger-subtle">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stats-value text-danger" id="rejected-projects">0</div>
                        <div class="stats-label">Rejected Projects</div>
                    </div>
                </div>
            </div>
            
            <div class="personal-info">
                <h4 class="section-title">Personal Information</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value" id="profile-phone"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Last Login</div>
                            <div class="info-value" id="profile-last-login"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Joined On</div>
                            <div class="info-value" id="profile-created-at"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Account Type</div>
                            <div class="info-value" id="profile-creator-role"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    
    <template id="channel-partner-template">
        <div class="profile-section">
            <div class="profile-header d-flex align-items-center">
                <div class="profile-avatar">
                    <span id="avatar-initial"></span>
                </div>
                <div>
                    <h2 id="profile-name" class="fw-bold mb-1"></h2>
                    <p class="mb-0 opacity-75" id="profile-email"></p>
                </div>
                <span class="badge-role" id="profile-role"></span>
            </div>
            
            <div class="row g-4 p-4">
                <div class="col-md-4">
                    <div class="profile-stats text-center">
                        <div class="stats-icon primary-subtle">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="stats-value" id="retailer-creation">0</div>
                        <div class="stats-label">Allowed Retailer Creation</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile-stats text-center">
                        <div class="stats-icon success-subtle">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stats-value text-success" id="distributor-creation">0</div>
                        <div class="stats-label">Allowed Distributor Creation</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile-stats text-center">
                        <div class="stats-icon info-subtle">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <div class="stats-value text-info" id="master-distributor-creation">0</div>
                        <div class="stats-label">Allowed Master Distributor Creation</div>
                    </div>
                </div>
            </div>
            
            <div class="personal-info">
                <h4 class="section-title">Personal Information</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value" id="profile-phone"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Last Login</div>
                            <div class="info-value" id="profile-last-login"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Joined On</div>
                            <div class="info-value" id="profile-created-at"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Account Type</div>
                            <div class="info-value" id="profile-creator-role"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Action cards template (common for both roles) -->
    <template id="action-cards-template">
        <div class="mt-4">
            <h4 class="section-title">Quick Actions</h4>
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <a href="commission_history.php" class="card-link">
                        <div class="card action-card">
                            <div class="card-body text-center">
                                <div class="card-icon mx-auto primary-subtle">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <h5 class="card-title fw-semibold">Commission History</h5>
                                <p class="card-text text-muted">View your earnings and commission details</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="raise_ticket.php" class="card-link">
                        <div class="card action-card">
                            <div class="card-body text-center">
                                <div class="card-icon mx-auto success-subtle">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                                <h5 class="card-title fw-semibold">Raise Ticket</h5>
                                <p class="card-text text-muted">Create a new support ticket</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="ticket_history.php" class="card-link">
                        <div class="card action-card">
                            <div class="card-body text-center">
                                <div class="card-icon mx-auto warning-subtle">
                                    <i class="fas fa-history"></i>
                                </div>
                                <h5 class="card-title fw-semibold">Ticket History</h5>
                                <p class="card-text text-muted">View your previous support tickets</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="logout.php" class="card-link">
                        <div class="card action-card">
                            <div class="card-body text-center">
                                <div class="card-icon mx-auto danger-subtle">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <h5 class="card-title fw-semibold">Logout</h5>
                                <p class="card-text text-muted">Sign out from your account</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </template>

   <!-- Contact us template -->
<template id="contact-template">
    <div class="contact-section">
        <h4 class="section-title">Contact Us</h4>
        <div class="row">
            <div class="col-lg-6">
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-info">
                        <div class="contact-label">Email Support</div>
                        <div class="contact-value">
                            <a href="mailto:support@evargoservices.com" style="color: inherit; text-decoration: none;">support@evargoservices.com</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="contact-info">
                        <div class="contact-label">Phone Support</div>
                        <div class="contact-value">
                            <a href="tel:+919082479920" style="color: inherit; text-decoration: none;">+91-9082479920</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Axios for API calls -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileContainer = document.getElementById('profile-content');
            const loading = document.getElementById('loading');
            
            // Function to format date
            function formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                return new Date(dateString).toLocaleDateString('en-US', options);
            }
            
            // Function to get the first letter of the name
            function getInitial(name) {
                return name ? name.charAt(0).toUpperCase() : '';
            }
            
            // Function to render Retailer profile
            function renderRetailerProfile(data) {
                const template = document.getElementById('retailer-template');
                const clone = template.content.cloneNode(true);
                
                // Set values
                clone.getElementById('profile-name').textContent = data.details.name;
                clone.getElementById('profile-email').textContent = data.details.email;
                clone.getElementById('profile-phone').textContent = data.details.phone_number || 'Not provided';
                clone.getElementById('profile-last-login').textContent = formatDate(data.details.last_login);
                clone.getElementById('profile-created-at').textContent = formatDate(data.details.created_at);
                clone.getElementById('profile-creator-role').textContent = data.details.creator_role;
                clone.getElementById('profile-role').textContent = data.role;
                clone.getElementById('avatar-initial').textContent = getInitial(data.details.name);
                
                // Project statistics
                clone.getElementById('total-projects').textContent = data.totalProjects;
                clone.getElementById('approved-projects').textContent = data.projectApproved;
                clone.getElementById('rejected-projects').textContent = data.projectRejected;
                
                // Add to the document
                profileContainer.appendChild(clone);
            }
            
            // Function to render Channel Partner profile
            function renderChannelPartnerProfile(data) {
                const template = document.getElementById('channel-partner-template');
                const clone = template.content.cloneNode(true);
                
                // Set values
                clone.getElementById('profile-name').textContent = data.details.name;
                clone.getElementById('profile-email').textContent = data.details.email;
                clone.getElementById('profile-phone').textContent = data.details.phone_number || 'Not provided';
                clone.getElementById('profile-last-login').textContent = formatDate(data.details.last_login);
                clone.getElementById('profile-created-at').textContent = formatDate(data.details.created_at);
                clone.getElementById('profile-creator-role').textContent = data.details.creator_role;
                clone.getElementById('profile-role').textContent = data.role;
                clone.getElementById('avatar-initial').textContent = getInitial(data.details.name);
                
                // Creation allowances
                clone.getElementById('retailer-creation').textContent = data.details.allowed_retailer_creation ?? 0;
                clone.getElementById('distributor-creation').textContent = data.details.allowed_distributor_creation ?? 0;
                clone.getElementById('master-distributor-creation').textContent = data.details.allowed_master_distributor_creation ?? 0;
                
                // Add to the document
                profileContainer.appendChild(clone);
            }
            
            // Function to add action cards and contact section
            function renderCommonSections() {
                // Add action cards
                const actionsTemplate = document.getElementById('action-cards-template');
                profileContainer.appendChild(actionsTemplate.content.cloneNode(true));
                
                // Add contact section
                const contactTemplate = document.getElementById('contact-template');
                profileContainer.appendChild(contactTemplate.content.cloneNode(true));
            }
            
            // Fetch profile data
            axios.get('https://backend.evargo.solarportal.in/evargo/api/v1/agent/profile', {
                headers: { 'Authorization': 'Bearer ' + sessionStorage.getItem('access_token') }
            })
            .then(response => {
                const data = response.data.data;
                
                // Determine which template to use based on role
                if (data.role === 'Retailer') {
                    renderRetailerProfile(data);
                } else  {
                    renderChannelPartnerProfile(data);
                }
                
                // Add common sections
                renderCommonSections();
                
                // Show the profile and hide loading
                loading.style.display = 'none';
                profileContainer.style.display = 'block';
            })
            .catch(error => {
                console.error('Error fetching profile:', error);
                profileContainer.innerHTML = `
                    <div class="alert alert-custom">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <h5 class="mb-1 fw-bold">Unable to Load Profile</h5>
                            <p class="mb-0">We're having trouble loading your profile data. Please try refreshing the page or contact support if the issue persists.</p>
                        </div>
                    </div>
                `;
                loading.style.display = 'none';
                profileContainer.style.display = 'block';
            });
        });
    </script>
</body>
</html>