<?php
session_start();
if (!isset($_SESSION['access_token']) || !isset($_SESSION['role'])) {
    header("Location: index.php");
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVargo Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #A945BB;
            --secondary-color: #f8f9fa;
            --accent-color: #FFD30F;
            --dark-color: #393939;
            --light-color: #ffffff;
            --success-color: #34a853;
            --warning-color: #fbbc05;
            --danger-color: #ea4335;
            --border-radius: 12px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            min-height: 100vh;
        }

        /* Main Content */
        .main-content {
            padding: 1.5rem;
        }

        /* Banner Component */
        .banner-carousel {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            margin-bottom: 1.5rem;
        }

        .banner-carousel .carousel-inner img {
            border-radius: 0;
            width: 100%;
            object-fit: cover;
            height: 200px;
            /* Default height for mobile */
        }

        /* Medium devices (tablets, 768px and up) */
        @media (min-width: 768px) {
            .banner-carousel .carousel-inner img {
                height: 300px;
                /* Medium size for tablets */
            }
        }

        /* Large devices (desktops, 992px and up) */
        @media (min-width: 992px) {
            .banner-carousel .carousel-inner img {
                height: 400px;
                /* Full height for desktop */
            }
        }

        /* Welcome Section Component */
        .welcome-section {
            background-color: var(--light-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--box-shadow);
            border-left: 5px solid var(--primary-color);
        }

        .welcome-heading {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .welcome-subheading {
            color: #666;
            margin-bottom: 1rem;
        }

        /* Custom buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #8A37A0;
            border-color: #8A37A0;
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* Dashboard Cards Component */
        .dashboard-cards {
            margin-bottom: 1.5rem;
        }

        .dashboard-card {
            background-color: var(--light-color);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 100%;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .card-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .card-content {
            display: flex;
            align-items: center;
            padding: 1.25rem;
        }

        .card-info h3 {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }

        .card-info p {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .card-bg-primary .card-icon {
            background-color: rgba(169, 69, 187, 0.1);
            color: var(--primary-color);
        }

        .card-bg-success .card-icon {
            background-color: rgba(255, 211, 15, 0.1);
            color: var(--accent-color);
        }

        .card-bg-warning .card-icon {
            background-color: rgba(255, 211, 15, 0.1);
            color: var(--accent-color);
        }

        .card-bg-danger .card-icon {
            background-color: rgba(57, 57, 57, 0.1);
            color: var(--dark-color);
        }

        /* Stats Component */
        .stats-section {
            background-color: var(--light-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--box-shadow);
            border-top: 4px solid var(--primary-color);
        }

        .stats-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* Quick Actions Component */
        .quick-actions {
            background-color: var(--light-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--box-shadow);
            border-top: 4px solid var(--accent-color);
        }

        .action-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            background-color: var(--secondary-color);
            border-radius: var(--border-radius);
            transition: var(--transition);
            text-decoration: none;
            color: var(--dark-color);
            height: 100%;
        }

        .action-button:hover {
            background-color: rgba(169, 69, 187, 0.08);
            color: var(--primary-color);
            transform: translateY(-3px);
        }

        .action-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .action-label {
            font-weight: 500;
            text-align: center;
        }

        /* Role Badge Component */
        .role-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            background-color: var(--accent-color);
            color: var(--dark-color);
            font-weight: 500;
            font-size: 0.85rem;
            margin-left: 0.75rem;
        }
    </style>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>

    <!-- Main Content -->
    <div class="container main-content">
        <!-- Banner Component -->
        <div class="banner-carousel">
            <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="assets/png/notf-1.png" class="d-block w-100" alt="Banner 1">
                    </div>
                    <div class="carousel-item">
                        <img src="assets/png/notf-2.png" class="d-block w-100" alt="Banner 2">
                    </div>
                    <div class="carousel-item">
                        <img src="assets/png/notf-3.png" class="d-block w-100" alt="Banner 3">
                    </div>
                    <div class="carousel-item">
                        <img src="assets/png/notf-4.png" class="d-block w-100" alt="Banner 3">
                    </div>
                    <div class="carousel-item">
                        <img src="assets/png/notf-5.png" class="d-block w-100" alt="Banner 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

        <!-- Welcome Section Component -->
        <div class="welcome-section">
            <h2 class="welcome-heading">Welcome, <span id="welcomeName">User</span>!</h2>
            <p class="welcome-subheading">Here's an overview of your Evargo Services account performance and activities.</p>
            <div id="welcomeActionButtons"></div>
        </div>

        <!-- Dashboard Cards Component -->
        <div class="dashboard-cards mb-4">
            <div id="dashboardContent">
                <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                    <div class="spinner-border" style="color: var(--primary-color);" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="ms-3">Loading dashboard data...</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions Component -->
        <div class="quick-actions">
            <h3 class="stats-title">Quick Actions</h3>
            <div class="row g-3" id="quickActions">
                <!-- Actions will be populated here based on role -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const role = "<?php echo $role; ?>";
            const userName = document.getElementById('userName');
            const welcomeName = document.getElementById('welcomeName');
            const roleBadge = document.getElementById('roleBadge');
            const quickActions = document.getElementById('quickActions');
            const welcomeActionButtons = document.getElementById('welcomeActionButtons');

            // Display role badge for all users
            if (roleBadge) {
                roleBadge.textContent = role;
                roleBadge.classList.remove('d-none');
            }

            if (role === "Retailer") {
                // Set quick actions for retailers
                quickActions.innerHTML = `
                    <div class="col-6 col-md-3">
                        <a href="add_project.php" class="action-button">
                            <div class="action-icon"><i class="fas fa-plus-circle"></i></div>
                            <div class="action-label">Create New Project</div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="raise_ticket.php" class="action-button">
                            <div class="action-icon"><i class="fas fa-ticket-alt"></i></div>
                            <div class="action-label">Raise Support Ticket</div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="commission_history.php" class="action-button">
                            <div class="action-icon"><i class="fas fa-wallet"></i></div>
                            <div class="action-label">View Commission</div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
    <a href="certificate.php" class="action-button">
        <div class="action-icon"><i class="fas fa-certificate"></i></div>
        <div class="action-label">Download Certificate</div>
    </a>
</div>
                `;

                // Welcome section action button for retailers
                welcomeActionButtons.innerHTML = `
                    <a href="#" class="btn btn-primary">Create New Project</a>
                    <a href="#" class="btn btn-outline-primary ms-2">View All Projects</a>
                `;
            } else {
                // Set quick actions for agents/admins
                const roleName = role === "Distributor" ? "Retailer" :
                    role === "Master Distributor" ? "Distributor" : "Partner";

                quickActions.innerHTML = `
                    <div class="col-6 col-md-3">
                        <a href="create_agent.php" class="action-button">
                            <div class="action-icon"><i class="fas fa-user-plus"></i></div>
                            <div class="action-label">Create New Agent</div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="raise_ticket.php" class="action-button">
                            <div class="action-icon"><i class="fas fa-ticket-alt"></i></div>
                            <div class="action-label">Raise Support Ticket</div>
                        </a>
                    </div>
                      <div class="col-6 col-md-3">
                        <a href="commission_history.php" class="action-button">
                            <div class="action-icon"><i class="fas fa-wallet"></i></div>
                            <div class="action-label">View Commission</div>
                        </a>
                    </div>
                   <div class="col-6 col-md-3">
    <a href="certificate.php" class="action-button">
        <div class="action-icon"><i class="fas fa-certificate"></i></div>
        <div class="action-label">Download Certificate</div>
    </a>
</div>
                `;

                // Welcome section action button for agents/admins
                welcomeActionButtons.innerHTML = `
                    <a href="#" class="btn btn-primary">Create New Agent</a>
                    
                `;
            }

            // Fetch dashboard data
            axios.get('https://backend.evargo.solarportal.in/evargo/api/v1/agent/dashboard', {
                    headers: {
                        'Authorization': 'Bearer ' + sessionStorage.getItem('access_token')
                    }
                })
                .then(response => {
                    if (response.data.status === "success") {
                        const data = response.data.data;
                        let content = "";

                        // Set user name
                        if (userName) userName.textContent = data.name;
                        welcomeName.textContent = data.name;

                        if (role === "Retailer") {
                            content = `
                                <div class="row g-3">
                                    ${createCard("Total Projects", data.totalProjects, "fa-clipboard-list", "primary")}
                                    ${createCard("Approved Projects", data.projectApproved, "fa-check-circle", "success")}
                                    ${createCard("Rejected Projects", data.projectRejected, "fa-times-circle", "danger")}
                                    ${createCard("Tickets Raised", data.ticketRaised, "fa-ticket-alt", "warning")}
                                    ${createCard("Total Commission", "₹" + data.totalCommission, "fa-money-bill", "primary")}
                                    ${createCard("Withdrawable Amount", "₹" + data.withdrawable_amount, "fa-wallet", "success")}
                                </div>

                                <!-- Charts Row -->
                                <div class="row mt-4">
                                    <div class="col-md-6 mb-4">
                                        <div class="stats-section h-100">
                                            <h3 class="stats-title">Project Status Distribution</h3>
                                            <div class="chart-container" style="height: 250px;">
                                                <canvas id="projectStatusChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="stats-section h-100">
                                            <h3 class="stats-title">Financial Overview</h3>
                                            <div class="chart-container" style="height: 250px;">
                                                <canvas id="financialChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            document.getElementById("dashboardContent").innerHTML = content;

                            // Create project status pie chart
                            const projectStatusCtx = document.getElementById('projectStatusChart').getContext('2d');
                            new Chart(projectStatusCtx, {
                                type: 'pie',
                                data: {
                                    labels: ['Approved', 'Rejected', 'Pending'],
                                    datasets: [{
                                        data: [data.projectApproved, data.projectRejected, data.totalProjects - data.projectApproved - data.projectRejected],
                                        backgroundColor: [
                                            '#A945BB', // Primary purple
                                            '#393939', // Dark color
                                            '#FFD30F' // Accent yellow
                                        ],
                                        borderColor: [
                                            '#8A37A0', // Darker purple
                                            '#222222', // Darker gray
                                            '#DBAA00' // Darker yellow
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'right',
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    const label = context.label || '';
                                                    const value = context.formattedValue;
                                                    const dataset = context.dataset;
                                                    const total = dataset.data.reduce((acc, data) => acc + data, 0);
                                                    const percentage = Math.round((context.raw / total) * 100);
                                                    return `${label}: ${value} (${percentage}%)`;
                                                }
                                            }
                                        }
                                    }
                                }
                            });

                            // Create financial overview pie chart
                            const financialCtx = document.getElementById('financialChart').getContext('2d');
                            new Chart(financialCtx, {
                                type: 'pie',
                                data: {
                                    labels: ['Withdrawable', 'Pending'],
                                    datasets: [{
                                        data: [
                                            parseFloat(data.withdrawable_amount),
                                            parseFloat(data.totalCommission) - parseFloat(data.withdrawable_amount)
                                        ],
                                        backgroundColor: [
                                            '#FFD30F', // Accent yellow
                                            '#A945BB' // Primary purple
                                        ],
                                        borderColor: [
                                            '#DBAA00', // Darker yellow
                                            '#8A37A0' // Darker purple
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'right',
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    const label = context.label || '';
                                                    const value = '₹' + context.raw;
                                                    const dataset = context.dataset;
                                                    const total = dataset.data.reduce((acc, data) => acc + data, 0);
                                                    const percentage = Math.round((context.raw / total) * 100);
                                                    return `${label}: ${value} (${percentage}%)`;
                                                }
                                            }
                                        }
                                    }
                                }
                            });

                        } else {
                            content = `
                                <div class="row g-3">
                                    ${createCard("DB Creation Limit", data.dbCreationLimit, "fa-database", "primary")}
                                    ${createCard("Master DB Creation Limit", data.masterDbCreationLimit, "fa-server", "success")}
                                    ${createCard("Retailer Creation Limit", data.retailerCreationLimit, "fa-users", "warning")}
                                    ${createCard("Total Created Count", data.totalCreatedCount, "fa-list", "danger")}
                                    ${createCard("Tickets Raised", data.ticketRaised, "fa-ticket-alt", "primary")}
                                    ${createCard("Total Commission", "₹" + data.totalCommission, "fa-money-bill", "success")}
                                    ${createCard("Withdrawable Amount", "₹" + data.withdrawable_amount, "fa-wallet", "warning")}
                                </div>

                                <!-- Only Revenue Distribution Chart -->
                                <div class="row mt-4">
                                    <div class="col-md-6 mx-auto mb-4">
                                        <div class="stats-section h-100">
                                            <h3 class="stats-title">Revenue Distribution</h3>
                                            <div class="chart-container" style="height: 250px;">
                                                <canvas id="revenueDistributionChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            document.getElementById("dashboardContent").innerHTML = content;

                            // Setup revenue distribution pie chart for agents/admins
                            const revenueCtx = document.getElementById('revenueDistributionChart').getContext('2d');
                            new Chart(revenueCtx, {
                                type: 'pie',
                                data: {
                                    labels: ['Withdrawable', 'Pending'],
                                    datasets: [{
                                        data: [
                                            parseFloat(data.withdrawable_amount),
                                            parseFloat(data.totalCommission) - parseFloat(data.withdrawable_amount)
                                        ],
                                        backgroundColor: [
                                            '#FFD30F', // Accent yellow
                                            '#A945BB' // Primary purple
                                        ],
                                        borderColor: [
                                            '#DBAA00', // Darker yellow
                                            '#8A37A0' // Darker purple
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'right',
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    const label = context.label || '';
                                                    const value = '₹' + context.raw;
                                                    const dataset = context.dataset;
                                                    const total = dataset.data.reduce((acc, data) => acc + data, 0);
                                                    const percentage = Math.round((context.raw / total) * 100);
                                                    return `${label}: ${value} (${percentage}%)`;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    } else {
                        document.getElementById("dashboardContent").innerHTML = `
                            <div class="alert alert-danger rounded-3">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                ${response.data.message || "Failed to load dashboard data. Please try again later."}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById("dashboardContent").innerHTML = `
                        <div class="alert alert-danger rounded-3">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Something went wrong while connecting to the server. Please check your connection and try again.
                        </div>
                    `;
                });

            // Card component creator
            function createCard(title, value, icon, colorClass) {
                return `
                    <div class="col-sm-6 col-md-6 col-lg-4">
                        <div class="dashboard-card card-bg-${colorClass}">
                            <div class="card-content">
                                <div class="card-icon">
                                    <i class="fas ${icon}"></i>
                                </div>
                                <div class="card-info">
                                    <h3>${title}</h3>
                                    <p>${value}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>