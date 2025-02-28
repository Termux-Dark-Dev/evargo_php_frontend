<?php
session_start();
if (!isset($_SESSION['access_token']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
if($role !== "Retailer") {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Dashboard</title>
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <style>
        :root {
            --primary: #A945BB;            /* Purple */
            --primary-light: #C77CD4;      /* Lighter purple */
            --primary-dark: #8A2E9A;       /* Darker purple */
            --secondary: #FFD30F;          /* Yellow */
            --secondary-light: #FFDD4D;    /* Lighter yellow */
            --secondary-dark: #E0B700;     /* Darker yellow */
            --dark: #393939;               /* Dark gray */
            --dark-light: #555555;         /* Lighter dark */
            --light: #FFFFFF;              /* White */
            --gray-light: #F8F9FA;         /* Light gray for backgrounds */
            --success: #28a745;            /* Success color */
            --warning: #ffc107;            /* Warning color */
            --danger: #dc3545;             /* Danger color */
            --border-radius: 10px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background-color: var(--light);
            color: var(--dark);
            min-height: 100vh;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 20px 0;
            border-bottom: 4px solid var(--secondary);
            margin-bottom: 30px;
        }
        
        .dashboard-title {
            font-weight: 600;
            margin: 0;
            font-size: 1.8rem;
        }
        
        .dashboard-subtitle {
            opacity: 0.9;
            font-size: 1rem;
            margin-top: 5px;
        }
        
        .card {
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            transition: var(--transition);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(169, 69, 187, 0.15);
        }
        
        .card-header {
            background-color: var(--light);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: 15px 20px;
            font-size: 1.1rem;
            color: var(--primary-dark);
        }
        
        .card-body {
            padding: 20px;
        }
        
        .chart-container {
            height: 300px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            text-align: center;
            padding: 20px;
            border-top: 4px solid;
        }
        
        .summary-card.total {
            border-top-color: var(--primary);
        }
        
        .summary-card.amount {
            border-top-color: var(--secondary);
        }
        
        .summary-card.approved {
            border-top-color: var(--success);
        }
        
        .summary-card.pending {
            border-top-color: var(--warning);
        }
        
        .summary-card .icon {
            font-size: 2rem;
            margin-bottom: 10px;
            color: var(--primary);
        }
        
        .summary-card.amount .icon {
            color: var(--secondary-dark);
        }
        
        .summary-card.approved .icon {
            color: var(--success);
        }
        
        .summary-card.pending .icon {
            color: var(--warning);
        }
        
        .summary-card .count {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .summary-card .label {
            font-size: 0.9rem;
            color: var(--dark-light);
        }
        
        .search-box {
            border-radius: 50px;
            padding: 10px 20px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }
        
        .search-btn {
            border-radius: 50px;
            padding-left: 20px;
            padding-right: 20px;
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .search-btn:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .badge {
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
        }
        
        .badge-pending {
            background-color: rgba(255, 211, 15, 0.15);
            color: #E0B700;
            border: 1px solid var(--secondary);
        }
        
        .badge-approved {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
            border: 1px solid var(--success);
        }
        
        .badge-rejected {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
            border: 1px solid var(--danger);
        }
        
        .project-amount {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary);
        }
        
        .project-date {
            font-size: 0.9rem;
            color: var(--dark-light);
        }
        
        .project-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-warning {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: var(--dark);
        }
        
        .btn-warning:hover {
            background-color: var(--secondary-dark);
            border-color: var(--secondary-dark);
            color: var(--dark);
        }
        
        .bg-primary {
            background-color: var(--primary) !important;
        }
        
        /* Custom table styles */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table th {
            background-color: rgba(169, 69, 187, 0.05);
            color: var(--primary-dark);
            font-weight: 600;
            border-bottom: 2px solid rgba(169, 69, 187, 0.2);
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: rgba(169, 69, 187, 0.03);
        }
        
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0, #f8f8f8, #f0f0f0);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        
        .error-container {
            text-align: center;
            padding: 40px 20px;
        }
        
        .error-icon {
            font-size: 4rem;
            color: var(--danger);
            margin-bottom: 20px;
        }
        
        .loading-spinner {
            width: 40px;
            height: 40px;
            margin: 40px auto;
            border: 4px solid rgba(169, 69, 187, 0.1);
            border-radius: 50%;
            border-top: 4px solid var(--primary);
            animation: spin 1s linear infinite;
        }
        
        .no-projects {
            text-align: center;
            padding: 40px 20px;
            color: var(--dark-light);
        }
        
        .empty-icon {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 300px;
            padding: 15px 20px;
            border-radius: var(--border-radius);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transform: translateX(400px);
            transition: transform 0.4s ease-out;
            z-index: 1000;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification-error {
            background-color: var(--danger);
        }
        
        .notification-success {
            background-color: var(--success);
        }
        
        .notification-info {
            background-color: var(--primary);
        }
        
        /* Role indicator */
        .role-indicator {
            background-color: var(--primary-light);
            color: white;
            padding: 3px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            display: inline-block;
            margin-left: 10px;
        }
        
        /* Add Project Button */
        .btn-add-project {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--secondary);
            color: var(--dark);
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            transition: var(--transition);
            border: none;
            z-index: 99;
        }
        
        .btn-add-project:hover {
            transform: scale(1.1);
            background-color: var(--secondary-dark);
        }
        
        @media (max-width: 767px) {
            .dashboard-stats {
                margin-bottom: 20px;
            }
            
            .chart-container {
                height: 250px;
            }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- <div class="dashboard-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="dashboard-title">Project Dashboard</h1>
                <p class="dashboard-subtitle">Welcome back, Retailer <span class="role-indicator">Retailer</span></p>
            </div>
            <div class="d-none d-md-block">
                <form class="d-flex" onsubmit="event.preventDefault(); filterProjects();">
                    <input id="searchInput" class="form-control search-box me-2" type="search" placeholder="Search projects..." aria-label="Search">
                    <button class="btn search-btn" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</div> -->
<div style="height: 50px;"></div>

<div class="container">
    <!-- Stats Row -->
    <div class="row" id="statsRow">
        <!-- Stats will be populated dynamically -->
        <div class="col-md-3">
            <div class="card summary-card skeleton" style="height: 150px;"></div>
        </div>
        <div class="col-md-3">
            <div class="card summary-card skeleton" style="height: 150px;"></div>
        </div>
        <div class="col-md-3">
            <div class="card summary-card skeleton" style="height: 150px;"></div>
        </div>
        <div class="col-md-3">
            <div class="card summary-card skeleton" style="height: 150px;"></div>
        </div>
    </div>
    
    <!-- Mobile Search (visible only on small screens) -->
    <div class="d-block d-md-none mb-4">
        <form class="d-flex" onsubmit="event.preventDefault(); filterProjects();">
            <input id="searchInputMobile" class="form-control search-box me-2" type="search" placeholder="Search projects..." aria-label="Search">
            <button class="btn search-btn" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>
    
    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pie-chart-fill"></i> Projects by Status
                </div>
                <div class="card-body chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-graph-up"></i> Sales Amount Trend
                </div>
                <div class="card-body chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Project List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-list-task"></i> Project List</span>
                        <span id="projectCount" class="badge bg-primary">0 Projects</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="projectsList">
                        <!-- Loading state -->
                        <div id="loadingState">
                            <div class="skeleton" style="height: 30px; width: 100%;"></div>
                            <div class="skeleton" style="height: 100px; width: 100%; margin-top: 10px;"></div>
                            <div class="skeleton" style="height: 100px; width: 100%; margin-top: 10px;"></div>
                            <div class="skeleton" style="height: 100px; width: 100%; margin-top: 10px;"></div>
                        </div>
                        
                        <!-- Empty state -->
                        <div id="emptyState" class="no-projects" style="display: none;">
                            <div class="empty-icon">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <h4>No Projects Found</h4>
                            <p>We couldn't find any projects matching your criteria.</p>
                            <button class="btn btn-warning mt-3" onclick="showAddProjectModal()">
                                <i class="bi bi-plus-circle"></i> Create New Project
                            </button>
                        </div>
                        
                        <!-- Error state -->
                        <div id="errorState" class="error-container" style="display: none;">
                            <div class="error-icon">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <h4>Oops! Something went wrong</h4>
                            <p id="errorMessage">We encountered an error while fetching your projects.</p>
                            <button id="retryButton" class="btn btn-primary mt-3" onclick="fetchProjects()">
                                <i class="bi bi-arrow-clockwise"></i> Try Again
                            </button>
                        </div>
                        
                        <!-- Projects Table -->
                        <div id="projectsTable" style="display: none;">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="projectsTableBody">
                                        <!-- Projects will be listed here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Project Button -->
<button class="btn-add-project" onclick="showAddProjectModal()">
    <i class="bi bi-plus-lg"></i>
</button>

<!-- Notification -->
<div id="notification" class="notification">
    <div class="notification-content">
        <span id="notificationMessage"></span>
    </div>
</div>

<script>
    // Global variables
    let projects = [];
    let statusChart = null;
    let salesChart = null;
    let retryCount = 0;
    const MAX_RETRIES = 3;

    // Initialize the page
    $(document).ready(function() {
        fetchProjects();
        
        // Link mobile search with desktop search
        $("#searchInputMobile").on("input", function() {
            $("#searchInput").val($(this).val());
        });
        
        $("#searchInput").on("input", function() {
            $("#searchInputMobile").val($(this).val());
        });
    });

    // Fetch projects data from API
    function fetchProjects() {
        showLoading();
        
        axios.get("https://backend.evargo.solarportal.in/evargo/api/v1/agent/view-project", {
            headers: { 'Authorization': 'Bearer ' + sessionStorage.getItem('access_token') }
        })
        .then(response => {
            if (response.data.status === "success") {
                projects = response.data.data;
                retryCount = 0;
                processData(projects);
            } else {
                handleApiError("Failed to load projects: " + response.data.message);
            }
        })
        .catch(error => {
            console.error("Error fetching projects:", error);
            handleApiError(error.message);
        });
    }

    // Process and display the data
    function processData(data) {
        // Display projects list
        displayProjects(data);
        
        // Display stats
        displayStats(data);
        
        // Initialize charts
        initCharts(data);
    }

    // Display project list
    function displayProjects(data) {
        hideAllStates();
        
        if (data.length === 0) {
            $("#emptyState").show();
            $("#projectCount").text("0 Projects");
            return;
        }
        
        let tableHtml = "";
        data.forEach(project => {
            tableHtml += `
                <tr>
                    <td>
                        <div class="fw-bold">${project.sales_desc}</div>
                        <small class="text-muted">ID: ${project.id}</small>
                    </td>
                    <td>
                        <div>Sale: ${formatDate(project.sale_date)}</div>
                        <small class="text-muted">Created: ${formatDate(project.created_at)}</small>
                    </td>
                    <td>
                        <div class="project-amount">₹${project.sale_amount.toLocaleString()}</div>
                    </td>
                    <td>
                        <span class="badge ${getStatusBadgeClass(project.status)}">${getStatusText(project.status)}</span>
                    </td>
                </tr>
            `;
        });
        
        $("#projectsTableBody").html(tableHtml);
        $("#projectsTable").show();
        $("#projectCount").text(`${data.length} Project${data.length !== 1 ? 's' : ''}`);
    }

    // Display project statistics
    function displayStats(data) {
        // Count projects by status
        let pending = data.filter(p => p.status === 0).length;
        let approved = data.filter(p => p.status === 1).length;
        let rejected = data.filter(p => p.status === -1).length;
        
        // Calculate total amount
        let totalAmount = data.reduce((sum, project) => sum + project.sale_amount, 0);
        
        // Create HTML for stats
        let statsHtml = `
            <div class="col-md-3">
                <div class="card summary-card total">
                    <div class="icon"><i class="bi bi-collection"></i></div>
                    <div class="count">${data.length}</div>
                    <div class="label">Total Projects</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card amount">
                    <div class="icon"><i class="bi bi-currency-rupee"></i></div>
                    <div class="count">₹${totalAmount.toLocaleString()}</div>
                    <div class="label">Total Amount</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card approved">
                    <div class="icon"><i class="bi bi-check-circle"></i></div>
                    <div class="count">${approved}</div>
                    <div class="label">Approved Projects</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card summary-card pending">
                    <div class="icon"><i class="bi bi-hourglass-split"></i></div>
                    <div class="count">${pending}</div>
                    <div class="label">Pending Projects</div>
                </div>
            </div>
        `;
        
        $("#statsRow").html(statsHtml);
    }

    // Initialize charts
    function initCharts(data) {
        // Prepare data for status chart
        let statusData = {
            pending: data.filter(p => p.status === 0).length,
            approved: data.filter(p => p.status === 1).length,
            rejected: data.filter(p => p.status === -1).length
        };
        
        // Prepare data for sales trend chart
        let salesData = prepareChartData(data);
        
        // Create status chart
        createStatusChart(statusData);
        
        // Create sales trend chart
        createSalesChart(salesData);
    }

    // Create status distribution chart
    function createStatusChart(data) {
        const ctx = document.getElementById('statusChart').getContext('2d');
        
        if (statusChart) {
            statusChart.destroy();
        }
        
        statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Rejected'],
                datasets: [{
                    data: [data.pending, data.approved, data.rejected],
                    backgroundColor: ['#FFD30F', '#28a745', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Create sales trend chart
    function createSalesChart(data) {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        if (salesChart) {
            salesChart.destroy();
        }
        
        salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Sale Amount',
                    data: data.amounts,
                    backgroundColor: 'rgba(169, 69, 187, 0.2)',
                    borderColor: '#A945BB',
                    borderWidth: 2,
                    pointBackgroundColor: '#A945BB',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.raw || 0;
                                return `${label}: ₹${value.toLocaleString()}`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Prepare data for sales chart
    function prepareChartData(data) {
        // Sort projects by date
        let sortedProjects = [...data].sort((a, b) => {
            return new Date(a.sale_date) - new Date(b.sale_date);
        });
        
        // Extract dates and amounts
        let labels = sortedProjects.map(p => formatChartDate(p.sale_date));
        let amounts = sortedProjects.map(p => p.sale_amount);
        
        return {
            labels: labels,
            amounts: amounts
        };
    }

    // Filter projects based on search
    function filterProjects() {
        let searchText = $("#searchInput").val().toLowerCase();
        
        if (searchText.trim() === "") {
            displayProjects(projects);
            return;
        }
        
        let filtered = projects.filter(p => 
            p.sales_desc.toLowerCase().includes(searchText)
        );
        
        displayProjects(filtered);
    }

    // Handle API error
    function handleApiError(message) {
        hideAllStates();
        
        if (retryCount < MAX_RETRIES) {
            retryCount++;
            showNotification(`Error: ${message}. Retrying (${retryCount}/${MAX_RETRIES})...`, "error");
            
            // Auto retry after delay
            setTimeout(() => {
                fetchProjects();
            }, 3000);
        } else {
            $("#errorMessage").text(message);
            $("#errorState").show();
            showNotification("Failed to load projects after multiple attempts", "error");
        }
    }

    // Show loading state
    function showLoading() {
        hideAllStates();
        $("#loadingState").show();
    }

    // Hide all states
    function hideAllStates() {
        $("#loadingState").hide();
        $("#emptyState").hide();
        $("#errorState").hide();
        $("#projectsTable").hide();
    }

    // Show notification
    function showNotification(message, type = "info") {
        const notification = $("#notification");
        notification.removeClass("notification-info notification-success notification-error");
        notification.addClass(`notification-${type}`);
        $("#notificationMessage").text(message);
        notification.addClass("show");
        
        setTimeout(() => {
            notification.removeClass("show");
        }, 5000);
    }

    // Show Add Project Modal (placeholder for future implementation)
    function showAddProjectModal() {
        showNotification("Create new project feature coming soon!", "info");
    }

    // Helper function to format date
    function formatDate(dateString) {
        if (!dateString) return "N/A";
        
        // Check if date is in DD/MM/YYYY format
        if (dateString.includes("/")) {
            return dateString;
        }
        
        // If it's ISO format, convert to readable date
        return moment(dateString).format("DD/MM/YYYY");
    }

    // Helper function to format chart date
    function formatChartDate(dateString) {
        if (!dateString) return "N/A";
        
        // Check if date is in DD/MM/YYYY format
        if (dateString.includes("/")) {
            let parts = dateString.split("/");
            return `${parts[0]}/${parts[1]}`;
        }
        
        // If it's ISO format, convert to readable date
        return moment(dateString).format("DD/MM");
    }

    // Helper function to get status text
    function getStatusText(status) {
        switch (status) {
            case 0: return "Pending";
            case 1: return "Approved";
            case -1: return "Rejected";
            default: return "Unknown";
        }
    }

    // Helper function to get status badge class
    function getStatusBadgeClass(status) {
        switch (status) {
            case 0: return "badge-pending";
            case 1: return "badge-approved";
            case -1: return "badge-rejected";
            default: return "bg-secondary";
        }
    }
</script>

</body>
</html>