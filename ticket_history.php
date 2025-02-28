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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticket History | EVargo Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Brand Colors */
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
            --card-shadow: 0 8px 15px rgba(169, 69, 187, 0.1);
            --hover-shadow: 0 12px 25px rgba(169, 69, 187, 0.15);
            --transition-speed: 0.3s;
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
        .ticket-card {
            background-color: var(--white-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            border: none;
            cursor: pointer;
        }
        
        .ticket-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }
        
        .ticket-header {
            padding: 1.25rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white-color);
            position: relative;
        }
        
        .ticket-body {
            padding: 1.25rem;
        }
        
        .ticket-footer {
            padding: 1rem 1.25rem;
            background-color: rgba(169, 69, 187, 0.03);
            border-top: 1px solid rgba(169, 69, 187, 0.1);
        }
        
        .ticket-status {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            padding: 0.35rem 0.85rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-pending {
            background-color: var(--accent-color);
            color: var(--dark-color);
        }
        
        .status-open {
            background-color: #17a2b8;
            color: white;
        }
        
        .status-resolved {
            background-color: #28a745;
            color: white;
        }
        
        .ticket-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--white-color);
        }
        
        .ticket-date {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .ticket-info {
            margin-bottom: 0.75rem;
            display: flex;
            align-items: flex-start;
        }
        
        .ticket-info-icon {
            color: var(--primary-color);
            min-width: 24px;
            margin-right: 0.75rem;
            margin-top: 0.2rem;
        }
        
        .ticket-info-text {
            flex-grow: 1;
            word-break: break-word;
        }
        
        .ticket-comment {
            background-color: rgba(169, 69, 187, 0.03);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            position: relative;
            border-left: 3px solid var(--primary-color);
        }
        
        .ticket-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: var(--dark-light);
        }
        
        .ticket-meta-item {
            display: flex;
            align-items: center;
        }
        
        .ticket-meta-icon {
            margin-right: 0.5rem;
        }
        
        /* Filter Styles */
        .filter-container {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }
        
        .filter-title {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .filter-title i {
            margin-right: 0.75rem;
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .filter-input {
            border-radius: 8px;
            border: 1px solid #e0e6ed;
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .filter-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(169, 69, 187, 0.2);
        }
        
        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(169, 69, 187, 0.3);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Empty State */
        .empty-state {
            background-color: white;
            border-radius: 12px;
            padding: 3rem;
            text-align: center;
            box-shadow: var(--card-shadow);
        }
        
        .empty-state-icon {
            font-size: 3rem;
            color: var(--primary-light);
            margin-bottom: 1.5rem;
        }
        
        .empty-state-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
        }
        
        .empty-state-message {
            color: var(--dark-light);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Loading State */
        .loading-spinner {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 300px;
        }
        
        .spinner-primary {
            color: var(--primary-color);
            width: 3rem;
            height: 3rem;
        }
        
        /* Error State */
        .alert-custom {
            border-radius: 14px;
            padding: 1.8rem;
            display: flex;
            align-items: center;
            background-color: rgba(169, 69, 187, 0.1);
            border-left: 5px solid var(--primary-color);
            color: var(--dark-color);
            margin-bottom: 2rem;
        }
        
        .alert-custom i {
            font-size: 1.8rem;
            margin-right: 1.2rem;
            color: var(--primary-color);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.4s ease forwards;
        }
        
        /* Date Picker */
        .date-input-container {
            position: relative;
        }
        
        .date-input-container i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            pointer-events: none;
        }
        
        /* Mobile Responsiveness */
        @media (max-width: 767px) {
            .filter-container {
                padding: 1rem;
            }
            
            .empty-state {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container main-content">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-header text-center text-md-start py-3 px-4">
                    <h1 class="page-title">Ticket History</h1>
                    <p class="page-description">Track and manage your support tickets</p>
                </div>
            </div>
        </div>
        
        <!-- Action Button -->
        <div class="row mb-4">
            <div class="col-12 text-end">
                <a href="create_ticket.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Create New Ticket
                </a>
            </div>
        </div>
        
        <!-- Loading State -->
        <div id="loadingState" class="loading-spinner">
            <div class="spinner-border spinner-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading your tickets...</p>
        </div>
        
        <!-- Error State -->
        <div id="errorState" class="d-none">
            <div class="alert alert-custom">
                <i class="bi bi-exclamation-circle"></i>
                <div>
                    <h5 class="mb-1 fw-bold">Unable to Load Tickets</h5>
                    <p class="mb-2">We're having trouble loading your ticket data. Please try refreshing the page or contact support if the issue persists.</p>
                    <button id="retryButton" class="btn btn-outline-primary mt-2">
                        <i class="bi bi-arrow-clockwise me-2"></i>Retry
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Results State (Filters + Tickets) -->
        <div id="resultsState" class="d-none">
            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="filter-container">
                        <h5 class="filter-title">
                            <i class="bi bi-funnel"></i>Filter Tickets
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="searchInput" class="form-label">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="searchInput" class="form-control" placeholder="Search by ticket ID or content...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="statusFilter" class="form-label">Status</label>
                                <select id="statusFilter" class="form-select filter-input">
                                    <option value="all">All Statuses</option>
                                    <option value="-1">Pending</option>
                                    <option value="0">Open</option>
                                    <option value="1">Resolved</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="dateFilter" class="form-label">Date Range</label>
                                <div class="date-input-container">
                                    <select id="dateFilter" class="form-select filter-input">
                                        <option value="all">All Time</option>
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="week">This Week</option>
                                        <option value="month">This Month</option>
                                        <option value="year">This Year</option>
                                    </select>
                                    <i class="bi bi-calendar3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Ticket Count Summary -->
            <div class="row mb-3">
                <div class="col-12">
                    <p class="mb-0" id="ticketCountSummary">Showing all tickets</p>
                </div>
            </div>
            
            <!-- Tickets Grid -->
            <div class="row g-4" id="ticketsContainer">
                <!-- Ticket cards will be inserted here by JavaScript -->
            </div>
        </div>
        
        <!-- Empty State -->
        <div id="emptyState" class="d-none">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
                <h3 class="empty-state-title">No Tickets Found</h3>
                <p class="empty-state-message">You haven't created any support tickets yet or none match your search criteria.</p>
                <a href="create_ticket.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Create Your First Ticket
                </a>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetchTickets();
            
            // Add event listener for retry button
            document.getElementById('retryButton').addEventListener('click', fetchTickets);
            
            // Add event listeners for filters
            document.getElementById('searchInput').addEventListener('input', filterTickets);
            document.getElementById('statusFilter').addEventListener('change', filterTickets);
            document.getElementById('dateFilter').addEventListener('change', filterTickets);
            
            function fetchTickets() {
                // Show loading state, hide others
                document.getElementById('loadingState').classList.remove('d-none');
                document.getElementById('errorState').classList.add('d-none');
                document.getElementById('emptyState').classList.add('d-none');
                document.getElementById('resultsState').classList.add('d-none');
                
                // Fetch tickets from API
                fetch('https://backend.evargo.solarportal.in/evargo/api/v1/agent/fetch-tickets', {
                    method: 'GET',
                    headers: {
                        Authorization: 'Bearer ' + sessionStorage.getItem('access_token')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Hide loading state
                    document.getElementById('loadingState').classList.add('d-none');
                    
                    if (data.status === 'success' && data.data && data.data.length > 0) {
                        // Store the full ticket data for later use
                        window.allTickets = data.data;
                        
                        // Show results state
                        document.getElementById('resultsState').classList.remove('d-none');
                        
                        // Display tickets
                        displayTickets(data.data);
                        updateTicketCount(data.data.length, data.data.length);
                    } else {
                        // Show empty state
                        document.getElementById('emptyState').classList.remove('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Hide all other states
                    document.getElementById('loadingState').classList.add('d-none');
                    document.getElementById('resultsState').classList.add('d-none');
                    document.getElementById('emptyState').classList.add('d-none');
                    
                    // Show only error state
                    document.getElementById('errorState').classList.remove('d-none');
                });
            }
            
            function displayTickets(tickets) {
                const container = document.getElementById('ticketsContainer');
                
                // Clear existing content
                container.innerHTML = '';
                
                // Display tickets as cards
                tickets.forEach((ticket, index) => {
                    // Create card
                    const cardCol = document.createElement('div');
                    cardCol.className = 'col-md-6 col-lg-4 fade-in';
                    cardCol.style.animationDelay = `${index * 0.1}s`;
                    
                    // Determine status display
                    let statusClass, statusText;
                    switch (parseInt(ticket.status)) {
                        case -1:
                            statusClass = 'status-pending';
                            statusText = 'Pending';
                            break;
                        case 0:
                            statusClass = 'status-open';
                            statusText = 'Open';
                            break;
                        case 1:
                            statusClass = 'status-resolved';
                            statusText = 'Resolved';
                            break;
                        default:
                            statusClass = 'status-unknown';
                            statusText = 'Unknown';
                    }
                    
                    // Format dates
                    const createdDate = new Date(ticket.created_at);
                    const updatedDate = new Date(ticket.updated_at);
                    const formattedCreatedDate = formatDate(createdDate);
                    const formattedUpdatedDate = formatDate(updatedDate);
                    
                    // Truncate comment if it's too long
                    const truncatedComment = ticket.comment.length > 100
                        ? ticket.comment.substring(0, 100) + '...'
                        : ticket.comment;
                    
                    cardCol.innerHTML = `
                        <div class="ticket-card" data-ticket-id="${ticket.id}">
                            <div class="ticket-header">
                                <h3 class="ticket-title">Ticket #${ticket.id}</h3>
                                <div class="ticket-date">Created on ${formattedCreatedDate}</div>
                                <div class="ticket-status ${statusClass}">${statusText}</div>
                            </div>
                            <div class="ticket-body">
                                <div class="ticket-comment">
                                    ${truncatedComment}
                                </div>
                                <div class="ticket-meta">
                                    <div class="ticket-meta-item">
                                        <i class="bi bi-clock ticket-meta-icon"></i>
                                        Last updated: ${formattedUpdatedDate}
                                    </div>
                                </div>
                            </div>
                            <div class="ticket-footer">
                                <button class="btn btn-primary w-100">
                                    <i class="bi bi-eye me-2"></i>View Details
                                </button>
                            </div>
                        </div>
                    `;
                    
                    container.appendChild(cardCol);
                });
                
                // Add event listeners to ticket cards
                document.querySelectorAll('.ticket-card').forEach(card => {
                    card.addEventListener('click', function() {
                        const ticketId = this.getAttribute('data-ticket-id');
                        viewTicketDetails(ticketId);
                    });
                });
            }
            
            function formatDate(date) {
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                return date.toLocaleDateString('en-US', options);
            }
            
            function viewTicketDetails(ticketId) {
                // Find the ticket in our stored data
                const ticket = window.allTickets.find(t => t.id == ticketId);
                
                if (ticket) {
                    // Store ticket data in sessionStorage
                    sessionStorage.setItem('ticketData', JSON.stringify(ticket));
                    
                    // Redirect to ticket details page
                    window.location.href = 'ticket_details.php';
                } else {
                    console.error('Ticket not found:', ticketId);
                }
            }
            
            function filterTickets() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value;
                const dateFilter = document.getElementById('dateFilter').value;
                
                // Filter tickets based on search term, status, and date
                let filteredTickets = window.allTickets;
                const totalTickets = window.allTickets.length;
                
                // Apply status filter if not "all"
                if (statusFilter !== 'all') {
                    filteredTickets = filteredTickets.filter(ticket => 
                        ticket.status == statusFilter
                    );
                }
                
                // Apply date filter if not "all"
                if (dateFilter !== 'all') {
                    const now = new Date();
                    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                    
                    switch (dateFilter) {
                        case 'today':
                            filteredTickets = filteredTickets.filter(ticket => {
                                const ticketDate = new Date(ticket.created_at);
                                return ticketDate >= today;
                            });
                            break;
                            
                        case 'yesterday':
                            const yesterday = new Date(today);
                            yesterday.setDate(yesterday.getDate() - 1);
                            const dayAfterYesterday = new Date(today);
                            
                            filteredTickets = filteredTickets.filter(ticket => {
                                const ticketDate = new Date(ticket.created_at);
                                return ticketDate >= yesterday && ticketDate < dayAfterYesterday;
                            });
                            break;
                            
                        case 'week':
                            const lastWeek = new Date(today);
                            lastWeek.setDate(lastWeek.getDate() - 7);
                            
                            filteredTickets = filteredTickets.filter(ticket => {
                                const ticketDate = new Date(ticket.created_at);
                                return ticketDate >= lastWeek;
                            });
                            break;
                            
                        case 'month':
                            const lastMonth = new Date(today);
                            lastMonth.setMonth(lastMonth.getMonth() - 1);
                            
                            filteredTickets = filteredTickets.filter(ticket => {
                                const ticketDate = new Date(ticket.created_at);
                                return ticketDate >= lastMonth;
                            });
                            break;
                            
                        case 'year':
                            const lastYear = new Date(today);
                            lastYear.setFullYear(lastYear.getFullYear() - 1);
                            
                            filteredTickets = filteredTickets.filter(ticket => {
                                const ticketDate = new Date(ticket.created_at);
                                return ticketDate >= lastYear;
                            });
                            break;
                    }
                }
                
                // Apply search filter
                if (searchTerm) {
                    filteredTickets = filteredTickets.filter(ticket => 
                        ticket.id.toString().includes(searchTerm) || 
                        ticket.comment.toLowerCase().includes(searchTerm)
                    );
                }
                
                // Display filtered tickets
                displayTickets(filteredTickets);
                updateTicketCount(filteredTickets.length, totalTickets);
                
                // Show empty state if no results
                if (filteredTickets.length === 0) {
                    const container = document.getElementById('ticketsContainer');
                    container.innerHTML = `
                        <div class="col-12">
                            <div class="py-5 text-center">
                                <i class="bi bi-search text-muted" style="font-size: 2.5rem;"></i>
                                <h5 class="mt-3">No Tickets Match Your Filters</h5>
                                <p class="text-muted">Try adjusting your search criteria or clear the filters</p>
                                <button class="btn btn-outline-primary mt-2" onclick="clearFilters()">
                                    <i class="bi bi-x-circle me-2"></i>Clear Filters
                                </button>
                            </div>
                        </div>
                    `;
                }
            }
            
            function updateTicketCount(filteredCount, totalCount) {
                const element = document.getElementById('ticketCountSummary');
                if (filteredCount === totalCount) {
                    element.textContent = `Showing all ${totalCount} ticket${totalCount !== 1 ? 's' : ''}`;
                } else {
                    element.textContent = `Showing ${filteredCount} of ${totalCount} ticket${totalCount !== 1 ? 's' : ''}`;
                }
            }
            
            // Add to the window scope so it can be called from HTML
            window.clearFilters = function() {
                document.getElementById('searchInput').value = '';
                document.getElementById('statusFilter').value = 'all';
                document.getElementById('dateFilter').value = 'all';
                filterTickets();
            };
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>