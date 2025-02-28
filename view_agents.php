<?php
session_start();
if (!isset($_SESSION['access_token']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Include the navbar
include('navbar.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Agents - EVargo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* General Styles */
        :root {
            --primary-color: #A945BB;
            --primary-light: #C76AD4;
            --primary-dark: #8A35A3;
            --accent-color: #FFD30F;
            --accent-light: #FFE04D;
            --dark-color: #393939;
            --dark-light: #5A5A5A;
            --white-color: #FFFFFF;
            --light-bg: #f8f5fa;
        }

        body {
            background-color: var(--light-bg);
            color: var(--dark-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-content {
            padding: 2rem 0;
        }

        .page-header {
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            background: linear-gradient(135deg, rgba(169, 69, 187, 0.05), rgba(169, 69, 187, 0.1));
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
        }

        .page-title {
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .page-description {
            color: var(--dark-light);
            font-size: 1.05rem;
        }

        /* Card Styles */
        .agent-card {
            background-color: var(--white-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(169, 69, 187, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
        }

        .agent-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(169, 69, 187, 0.15);
        }

        .agent-card-header {
            padding: 1.25rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white-color);
            position: relative;
        }

        .agent-type-badge {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            padding: 0.35rem 0.85rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            background-color: var(--accent-color);
            color: var(--dark-color);
        }

        .agent-card-body {
            padding: 1.25rem;
        }

        .agent-info {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: flex-start;
        }

        .agent-info-icon {
            color: var(--primary-color);
            min-width: 24px;
            margin-right: 0.75rem;
            margin-top: 0.2rem;
        }

        .agent-info-text {
            flex-grow: 1;
            word-break: break-word;
        }

        .agent-info-label {
            font-size: 0.85rem;
            color: var(--dark-light);
            margin-bottom: 0.2rem;
        }

        .agent-name {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--white-color);
        }

        .agent-date {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .agent-actions {
            margin-top: 1.5rem;
            display: flex;
            justify-content: flex-end;
        }

        .btn-edit {
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(169, 69, 187, 0.3);
        }

        .btn-edit i {
            margin-right: 0.5rem;
        }

        /* Search and Filter */
        .search-container {
            background-color: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(169, 69, 187, 0.05);
        }

        .search-input {
            border-radius: 8px;
            border: 1px solid #e0e6ed;
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(169, 69, 187, 0.2);
        }

        .filter-dropdown {
            border-radius: 8px;
            border: 1px solid #e0e6ed;
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .filter-dropdown:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(169, 69, 187, 0.2);
        }

        /* No Agents Message */
        .no-agents-container {
            background-color: var(--white-color);
            border-radius: 12px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(169, 69, 187, 0.05);
        }

        .no-agents-icon {
            font-size: 3rem;
            color: var(--primary-light);
            margin-bottom: 1rem;
        }

        .no-agents-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .no-agents-message {
            color: var(--dark-light);
            margin-bottom: 1.5rem;
        }

        /* Modal Styling */
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .modal-title {
            font-weight: 700;
        }

        .modal-footer {
            border-top: none;
        }

        .btn-save {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }

        .btn-save:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(169, 69, 187, 0.3);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #e0e6ed;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(169, 69, 187, 0.2);
        }

        /* Loading States */
        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
        }

        .spinner-border {
            color: var(--primary-color);
            width: 3rem;
            height: 3rem;
        }

        /* Error Alert */
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        /* Success Alert */
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        /* Pagination */
        .pagination-container {
            margin-top: 2rem;
        }

        .pagination {
            justify-content: center;
        }

        .page-item .page-link {
            padding: 0.5rem 1rem;
            color: var(--primary-color);
            border-radius: 6px;
            margin: 0 0.2rem;
            border: 1px solid #e0e6ed;
            transition: all 0.3s ease;
        }

        .page-item .page-link:hover {
            background-color: rgba(169, 69, 187, 0.05);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.4s ease forwards;
        }

        /* Empty Agent Card (Add New) */
        .empty-agent-card {
            background-color: #f9f9f9;
            border: 2px dashed #e0e6ed;
            border-radius: 12px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .empty-agent-card:hover {
            background-color: rgba(169, 69, 187, 0.05);
            border-color: var(--primary-color);
            transform: translateY(-5px);
        }

        .empty-agent-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .empty-agent-text {
            font-weight: 600;
            color: var(--dark-color);
        }

        /* Primary button override */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            box-shadow: 0 4px 10px rgba(169, 69, 187, 0.3);
        }

        /* Update the spinner animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spinner-border {
            animation: spin 1s linear infinite;
        }

        .loader-spinner {
            width: 4rem;
            height: 4rem;
            border: 4px solid rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            border-top: 4px solid var(--primary-color);
            animation: spin 1s linear infinite;
        }
    </style>
</head>

<body>
    <!-- Main Content -->
    <div class="container main-content">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-header text-center text-md-start">
                    <h1 class="page-title">Agent Management</h1>
                    <p class="page-description">View and manage all your created agents</p>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="search-container">
                    <div class="row">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <input type="text" id="searchInput" class="search-input" placeholder="Search agents by name, email or phone...">
                        </div>
                        <div class="col-md-4">
                            <select id="typeFilter" class="filter-dropdown">
                                <option value="">All Types</option>
                                <option value="Retailer">Retailer</option>
                                <option value="ChannelPartner">Channel Partner</option>
                                <option value="Distributor">Distributor</option>
                                <option value="MasterDistributor">Master Distributor</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <div id="alertContainer" class="row" style="display: none;">
            <div class="col-12">
                <div id="alertBox" class="alert"></div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Success</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-3" id="successModalTitle">Operation Successful</h4>
                        <p id="successModalMessage">The operation was completed successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
        <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #b02a37); color: white;">
                        <h5 class="modal-title">Error</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-3" id="errorModalTitle">Operation Failed</h4>
                        <p id="errorModalMessage">An error occurred. Please try again.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="errorModalRetryBtn">Try Again</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agents Grid -->
        <div class="row" id="agentsList">
            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="col-12 loading-spinner">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <!-- Agents will be inserted here dynamically -->
        </div>

        <!-- Empty State - No Agents -->
        <div id="noAgentsContainer" class="row" style="display: none;">
            <div class="col-12">
                <div class="no-agents-container">
                    <div class="no-agents-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="no-agents-title">No Agents Found</h3>
                    <p class="no-agents-message">You haven't created any agents yet or none match your search criteria.</p>
                    <a href="create_agent.php" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Create New Agent
                    </a>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="row">
            <div class="col-12">
                <div class="pagination-container" id="paginationContainer" style="display: none;">
                    <ul class="pagination">
                        <!-- Pagination will be inserted here dynamically -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Agent Modal -->
    <div class="modal fade" id="editAgentModal" tabindex="-1" aria-labelledby="editAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAgentModalLabel">Edit Agent</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAgentForm">
                        <input type="hidden" id="agentId">
                        <input type="hidden" id="agentType">
                        <div class="mb-3">
                            <label for="agentName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="agentName" placeholder="Enter agent name">
                        </div>
                        <div class="mb-3">
                            <label for="agentEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="agentEmail" placeholder="Enter agent email">
                        </div>
                        <div class="mb-3">
                            <label for="agentPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="agentPhone" placeholder="Enter agent phone">
                        </div>
                        <div class="mb-3">
                            <label for="agentPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="agentPassword" placeholder="Enter new password">
                            <div class="form-text text-muted">For security reasons, current password is not displayed. Enter a new password only if you want to change it.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-save" id="saveAgentBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const agentsList = document.getElementById('agentsList');
            const noAgentsContainer = document.getElementById('noAgentsContainer');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const searchInput = document.getElementById('searchInput');
            const typeFilter = document.getElementById('typeFilter');
            const alertContainer = document.getElementById('alertContainer');
            const alertBox = document.getElementById('alertBox');

            // Modal Elements
            const editAgentModal = new bootstrap.Modal(document.getElementById('editAgentModal'));
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            const agentIdInput = document.getElementById('agentId');
            const agentNameInput = document.getElementById('agentName');
            const agentEmailInput = document.getElementById('agentEmail');
            const agentPhoneInput = document.getElementById('agentPhone');
            const agentTypeInput = document.getElementById('agentType');
            const saveAgentBtn = document.getElementById('saveAgentBtn');
            const errorModalRetryBtn = document.getElementById('errorModalRetryBtn');

            // Keep track of original values for each field to know what was changed
            let originalValues = {};

            // Store all agents data
            let allAgents = [];
            let filteredAgents = [];

            // Format date function
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            // Show alert function
            function showAlert(message, type) {
                alertBox.className = `alert alert-${type}`;
                alertBox.innerHTML = message;
                alertContainer.style.display = 'block';

                // Auto hide after 5 seconds
                setTimeout(() => {
                    alertContainer.style.display = 'none';
                }, 5000);
            }

            // Function to fetch agents
            function fetchAgents() {
                loadingSpinner.style.display = 'flex';
                agentsList.innerHTML = '';
                noAgentsContainer.style.display = 'none';

                axios.get('https://backend.evargo.solarportal.in/evargo/api/v1/agent/view-created-agents', {
                        headers: {
                            'Authorization': 'Bearer ' + sessionStorage.getItem('access_token')
                        }
                    })
                    .then(response => {
                        loadingSpinner.style.display = 'none';

                        if (response.data.status === "success") {
                            allAgents = response.data.data;
                            filteredAgents = [...allAgents];

                            if (allAgents.length > 0) {
                                renderAgents(allAgents);
                            } else {
                                noAgentsContainer.style.display = 'block';
                            }
                        } else {
                            showAlert('Failed to load agents: ' + response.data.message, 'danger');
                            noAgentsContainer.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        loadingSpinner.style.display = 'none';
                        console.error('Error fetching agents:', error);
                        showAlert('Error loading agents. Please try again.', 'danger');
                        noAgentsContainer.style.display = 'block';
                    });
            }

            // Function to render agents
            function renderAgents(agents) {
                agentsList.innerHTML = '';

                if (agents.length === 0) {
                    noAgentsContainer.style.display = 'block';
                    return;
                }

                noAgentsContainer.style.display = 'none';

                // Create a new row for every 3 agents
                agents.forEach((agent, index) => {
                    const col = document.createElement('div');
                    col.className = 'col-md-6 col-lg-4 mb-4 fade-in';
                    col.style.animationDelay = `${index * 0.1}s`;

                    const formattedDate = formatDate(agent.created_at);

                    // Map agent type values to more readable formats if needed
                    let displayRole = agent.type;
                    if (agent.type === "MasterDistributor") {
                        displayRole = "Master Distributor";
                    }

                    col.innerHTML = `
                        <div class="agent-card">
                            <div class="agent-card-header">
                                <h3 class="agent-name">${agent.name}</h3>
                                <div class="agent-date">Created: ${formattedDate}</div>
                                <div class="agent-type-badge">${displayRole}</div>
                            </div>
                            <div class="agent-card-body">
                                <div class="agent-info">
                                    <div class="agent-info-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="agent-info-text">
                                        <div class="agent-info-label">Email</div>
                                        <div>${agent.email}</div>
                                    </div>
                                </div>
                                <div class="agent-info">
                                    <div class="agent-info-icon">
                                        <i class="bi bi-phone"></i>
                                    </div>
                                    <div class="agent-info-text">
                                        <div class="agent-info-label">Phone</div>
                                        <div>${agent.phone}</div>
                                    </div>
                                </div>
                                <div class="agent-actions">
                                    <button class="btn btn-edit" data-agent-id="${agent.id}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                    agentsList.appendChild(col);
                });

                // Add "Create New Agent" card if there's less than 10 agents
                if (agents.length < 10) {
                    const newAgentCol = document.createElement('div');
                    newAgentCol.className = 'col-md-6 col-lg-4 mb-4 fade-in';
                    newAgentCol.style.animationDelay = `${agents.length * 0.1}s`;

                    newAgentCol.innerHTML = `
                        <a href="create_agent.php" class="empty-agent-card">
                            <div class="empty-agent-icon">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div class="empty-agent-text">Create New Agent</div>
                        </a>
                    `;

                    agentsList.appendChild(newAgentCol);
                }

                // Add event listeners for edit buttons
                document.querySelectorAll('.btn-edit').forEach(button => {
                    button.addEventListener('click', function() {
                        const agentId = this.getAttribute('data-agent-id');
                        openEditModal(agentId);
                    });
                });
            }

            // Function to open edit modal
            function openEditModal(agentId) {
                const agent = allAgents.find(a => a.id == agentId);

                if (agent) {
                    // Store original values to track changes
                    originalValues = {
                        id: agent.id,
                        name: agent.name,
                        email: agent.email,
                        phone: agent.phone,
                        type: agent.type
                    };

                    // Fill form fields
                    agentIdInput.value = agent.id;
                    agentNameInput.value = agent.name;
                    agentEmailInput.value = agent.email;
                    agentPhoneInput.value = agent.phone;
                    agentTypeInput.value = agent.type; // This is now a hidden field

                    // Show modal
                    editAgentModal.show();
                }
            }

            // Function to update agent
            function updateAgent() {
                const agentId = parseInt(agentIdInput.value);
                const agentPassword = document.getElementById('agentPassword');
                const agentRole = agentTypeInput.value;

                // Prepare update data according to backend structure
                const updateData = {
                    agent_id: agentId,
                    agent_role: agentRole
                };

                // Only include fields that were changed or filled in
                if (agentNameInput.value !== originalValues.name) {
                    updateData.agent_name = agentNameInput.value;
                }

                if (agentEmailInput.value !== originalValues.email) {
                    updateData.agent_email = agentEmailInput.value;
                }

                if (agentPhoneInput.value !== originalValues.phone) {
                    updateData.phone_number = agentPhoneInput.value;
                }

                // Add password if provided
                if (agentPassword.value) {
                    updateData.agent_password = agentPassword.value;
                }

                // Check if there are any actual changes (beyond agent_id and agent_role which are required)
                const hasChanges = updateData.agent_name ||
                    updateData.agent_email ||
                    updateData.phone_number ||
                    updateData.agent_password;

                // If nothing changed, just close the modal
                if (!hasChanges) {
                    editAgentModal.hide();
                    return;
                }

                // Add ID to update data
                updateData.id = parseInt(agentId);

                // Show loading state on button
                saveAgentBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
                saveAgentBtn.disabled = true;

                axios.post('https://backend.evargo.solarportal.in/evargo/api/v1/agent/update-agent', updateData, {
                        headers: {
                            'Authorization': 'Bearer ' + sessionStorage.getItem('access_token')
                        }
                    })
                    .then(response => {
                        editAgentModal.hide();

                        if (response.data.status === "success") {
                            // Show success modal instead of alert
                            document.getElementById('successModalTitle').textContent = 'Agent Updated';
                            document.getElementById('successModalMessage').textContent = 'The agent has been successfully updated.';
                            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                            successModal.show();

                            // Refresh agent list
                            fetchAgents();
                        } else {
                            // Show error modal
                            document.getElementById('errorModalTitle').textContent = 'Update Failed';
                            document.getElementById('errorModalMessage').textContent = response.data.message || 'Failed to update agent. Please try again.';
                            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                            errorModal.show();
                        }
                    })
                    .catch(error => {
                        console.error('Error updating agent:', error);

                        // Show error modal
                        document.getElementById('errorModalTitle').textContent = 'Update Failed';
                        document.getElementById('errorModalMessage').textContent = error.response?.data?.message || 'An error occurred while updating the agent. Please try again.';
                        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();

                        editAgentModal.hide();
                    })
                    .finally(() => {
                        // Reset button
                        saveAgentBtn.innerHTML = 'Save Changes';
                        saveAgentBtn.disabled = false;
                    });
            }

            // Function to filter agents
            function filterAgents() {
                const searchTerm = searchInput.value.toLowerCase();
                const typeValue = typeFilter.value;

                filteredAgents = allAgents.filter(agent => {
                    const matchesSearch = agent.name.toLowerCase().includes(searchTerm) ||
                        agent.email.toLowerCase().includes(searchTerm) ||
                        agent.phone.toLowerCase().includes(searchTerm);

                    const matchesType = typeValue === '' || agent.type === typeValue;

                    return matchesSearch && matchesType;
                });

                renderAgents(filteredAgents);
            }

            // Event Listeners
            searchInput.addEventListener('input', filterAgents);
            typeFilter.addEventListener('change', filterAgents);
            saveAgentBtn.addEventListener('click', updateAgent);

            // Retry button in error modal
            errorModalRetryBtn.addEventListener('click', function() {
                errorModal.hide();
                // Wait for the modal to close before showing the edit modal again
                setTimeout(() => {
                    editAgentModal.show();
                }, 500);
            });

            // Reset modal form on close
            document.getElementById('editAgentModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('editAgentForm').reset();
            });

            // Initial load
            fetchAgents();
        });
    </script>
</body>

</html>