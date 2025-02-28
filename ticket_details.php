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
    <title>Ticket Details | EVargo Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #A945BB;
            --primary-light: #C76AD4;
            --primary-dark: #8A35A3;
            --accent: #FFD30F;
            --accent-light: #FFE04D;
            --dark: #393939;
            --dark-light: #5A5A5A;
            --light-bg: #f8f5fa;
            --card-shadow: 0 0.5rem 1rem rgba(169, 69, 187, 0.12);
            --card-border-radius: 0.75rem;
            --transition: all 0.3s ease;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark);
        }
        
        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            text-decoration: underline;
        }
        
        .card {
            border: none;
            border-radius: var(--card-border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: var(--transition);
        }
        
        .card:hover {
            box-shadow: 0 0.75rem 1.5rem rgba(169, 69, 187, 0.18);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid rgba(169, 69, 187, 0.1);
        }
        
        .main-card {
            border-top: 4px solid var(--primary);
        }
        
        .document-card {
            transition: var(--transition);
        }
        
        .document-card:hover {
            transform: translateY(-5px);
        }
        
        .badge.status-badge {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
            font-weight: 600;
        }
        
        .status-open {
            background-color: rgba(169, 69, 187, 0.15);
            color: var(--primary);
        }
        
        .status-resolved {
            background-color: rgba(25, 135, 84, 0.15);
            color: #198754;
        }
        
        .status-pending {
            background-color: rgba(255, 211, 15, 0.2);
            color: #997a00;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            box-shadow: 0 0.25rem 0.75rem rgba(169, 69, 187, 0.25);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(169, 69, 187, 0.35);
        }
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-2px);
        }
        
        .icon-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(169, 69, 187, 0.1);
            color: var(--primary);
            margin-right: 1rem;
        }
        
        .text-primary {
            color: var(--primary) !important;
        }
        
        .detail-item {
            padding: 1rem;
            border-radius: 0.5rem;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(169, 69, 187, 0.1);
            transition: var(--transition);
        }
        
        .detail-item:hover {
            background: white;
            border-color: rgba(169, 69, 187, 0.2);
            transform: translateY(-2px);
        }
        
        .description-container {
            background-color: white;
            border-radius: 0.5rem;
            border: 1px solid rgba(169, 69, 187, 0.1);
            padding: 1.25rem;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 60px;
            background-color: var(--primary);
            border-radius: 3px;
        }
        
        .admin-comment {
            background-color: white;
            border-radius: 0.75rem;
            border-left: 4px solid var(--primary);
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 0.25rem 0.75rem rgba(169, 69, 187, 0.08);
            transition: var(--transition);
        }
        
        .admin-comment:hover {
            box-shadow: 0 0.5rem 1rem rgba(169, 69, 187, 0.15);
            transform: translateY(-3px);
        }
        
        .admin-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }
        
        .help-card {
            background: linear-gradient(135deg, rgba(169, 69, 187, 0.05), rgba(169, 69, 187, 0.1));
            border: none;
            border-left: 4px solid var(--accent);
        }
        
        .upload-section {
            border-top: 4px solid var(--accent);
        }
        
        .empty-state {
            padding: 2.5rem;
            text-align: center;
            color: var(--dark-light);
        }
        
        .empty-state i {
            font-size: 3rem;
            color: rgba(169, 69, 187, 0.2);
            margin-bottom: 1rem;
        }
        
        .image-container {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        }
        
        .image-container img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .image-container:hover img {
            transform: scale(1.05);
        }
        
        .timestamp-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background-color: rgba(255, 255, 255, 0.9);
            color: var(--dark);
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="ticket_history.php">Support Tickets</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ticket Details</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Ticket Information Column -->
        <div class="col-lg-8">
            <div class="card main-card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <span class="icon-circle">
                            <i class="bi bi-ticket-detailed-fill"></i>
                        </span>
                        Ticket #<span id="ticket_id"></span>
                    </h5>
                    <span id="statusBadge" class="badge status-badge rounded-pill"></span>
                </div>
                <div class="card-body">
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="detail-item">
                                <p class="text-muted mb-1">Submitted by</p>
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bi bi-person-circle text-primary me-2"></i>
                                    <span id="creator_id"></span>
                                </h6>
                                <p class="mb-0 small text-muted"><span id="creator_role"></span></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-item">
                                <p class="text-muted mb-1">Submission Date</p>
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bi bi-calendar-event text-primary me-2"></i>
                                    <span id="created_at"></span>
                                </h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-item">
                                <p class="text-muted mb-1">Last Updated</p>
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bi bi-clock-history text-primary me-2"></i>
                                    <span id="updated_at"></span>
                                </h6>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="section-title fw-bold">
                        <i class="bi bi-chat-left-text text-primary me-2"></i>
                        Ticket Description
                    </h6>
                    <div class="description-container mb-4">
                        <p id="comment" class="mb-0"></p>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <span class="icon-circle">
                            <i class="bi bi-images"></i>
                        </span>
                        Attached Documents
                    </h5>
                </div>
                <div class="card-body">
                    <div id="documentsContainer" class="row g-4"></div>
                </div>
            </div>

            <!-- Upload Section - Only visible for Open and Pending tickets -->
            <div id="uploadSection" class="card shadow-sm mb-4 upload-section d-none">
                <div class="card-header py-3">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <span class="icon-circle">
                            <i class="bi bi-cloud-upload"></i>
                        </span>
                        Upload New Document
                    </h5>
                </div>
                <div class="card-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <input type="hidden" id="ticket_id_field" name="ticket_id">
                        <div class="mb-3">
                            <label for="documentFile" class="form-label fw-semibold">Select Document (Max 1MB)</label>
                            <input class="form-control" type="file" id="documentFile" name="file" accept="image/*" required>
                            <div class="form-text text-muted">Supported formats: JPG, PNG, GIF</div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-upload me-2"></i>
                            <span class="spinner-border spinner-border-sm d-none" id="loadingSpinner" role="status" aria-hidden="true"></span>
                            Upload Document
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Admin Comments Column -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <span class="icon-circle">
                            <i class="bi bi-chat-square-text"></i>
                        </span>
                        Admin Feedback
                    </h5>
                </div>
                <div class="card-body">
                    <div id="adminCommentsContainer" class="mb-0"></div>
                </div>
            </div>
            
            <div class="card help-card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="d-flex align-items-center mb-3 fw-bold">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        Need Help?
                    </h6>
                    <p>If you have any questions about this ticket, please contact our support team for assistance.</p>
                    <a href="contact.php" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-headset me-2"></i>
                        Contact Support
                    </a>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="d-flex align-items-center mb-3 fw-bold">
                        <i class="bi bi-clock-history text-primary me-2"></i>
                        Ticket Timeline
                    </h6>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="d-flex mb-3">
                                <div class="timeline-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <i class="bi bi-plus-circle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">Ticket Created</h6>
                                    <p class="text-muted small mb-0" id="timeline_created"></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="timeline-item" id="timeline_updated_container" style="display: none;">
                            <div class="d-flex">
                                <div class="timeline-icon bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <i class="bi bi-pencil"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">Last Updated</h6>
                                    <p class="text-muted small mb-0" id="timeline_updated"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Check if ticket data exists in sessionStorage
    const ticketData = sessionStorage.getItem('ticketData');
    
    if (!ticketData) {
        alert("No ticket data found!");
        window.location.href = "dashboard.php";
        return;
    }

    const ticket = JSON.parse(ticketData);
    
    // Populate ticket details
    document.getElementById('ticket_id').textContent = ticket.id;
    document.getElementById('comment').textContent = ticket.comment;
    document.getElementById('creator_id').textContent = ticket.creator_id;
    document.getElementById('creator_role').textContent = ticket.creator_role;
    
    // Format dates for better readability
    const createdDate = new Date(ticket.created_at);
    const updatedDate = new Date(ticket.updated_at);
    
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit'
    };
    
    document.getElementById('created_at').textContent = createdDate.toLocaleDateString('en-US', options);
    document.getElementById('updated_at').textContent = updatedDate.toLocaleDateString('en-US', options);
    document.getElementById('ticket_id_field').value = ticket.id;
    
    // Timeline dates
    document.getElementById('timeline_created').textContent = createdDate.toLocaleDateString('en-US', options);
    
    // Only show updated timeline if different from created date
    if (updatedDate.getTime() > createdDate.getTime() + 60000) { // 1 minute difference
        document.getElementById('timeline_updated').textContent = updatedDate.toLocaleDateString('en-US', options);
        document.getElementById('timeline_updated_container').style.display = 'block';
    }

    // Set status badge with correct mapping
    const statusBadge = document.getElementById('statusBadge');
    const statusValue = parseInt(ticket.status);
    
    switch (statusValue) {
        case 0:
            statusBadge.textContent = "Open";
            statusBadge.classList.add("status-open");
            // Show upload section for Open tickets
            document.getElementById('uploadSection').classList.remove('d-none');
            break;
        case 1:
            statusBadge.textContent = "Resolved";
            statusBadge.classList.add("status-resolved");
            // Don't show upload section for Resolved tickets
            break;
        case -1:
            statusBadge.textContent = "Pending";
            statusBadge.classList.add("status-pending");
            // Show upload section for Pending tickets
            document.getElementById('uploadSection').classList.remove('d-none');
            break;
        default:
            statusBadge.textContent = "Unknown";
            statusBadge.classList.add("bg-secondary");
    }

    // Display documents
    const documentsContainer = document.getElementById('documentsContainer');
    if (ticket.documents && ticket.documents.length > 0) {
        ticket.documents.forEach(doc => {
            const colDiv = document.createElement('div');
            colDiv.className = "col-md-6 col-xl-4";
            
            const cardDiv = document.createElement('div');
            cardDiv.className = "document-card h-100";
            
            const imageContainer = document.createElement('div');
            imageContainer.className = "image-container";
            
            const imgElement = document.createElement('img');
            console.log("http://147.93.96.19:8001/" + doc.document_path);
            
            imgElement.src = "http://147.93.96.19:8001/" + doc.document_path;
            imgElement.alt = "Uploaded Document";
            
            const timestamp = document.createElement('div');
            timestamp.className = "timestamp-badge";
            timestamp.innerHTML = `<i class="bi bi-clock me-1"></i>${new Date(doc.uploaded_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}`;
            
            imageContainer.appendChild(imgElement);
            imageContainer.appendChild(timestamp);
            
            const cardBody = document.createElement('div');
            cardBody.className = "mt-3";
            
            const downloadLink = document.createElement('a');
            downloadLink.href = "https://evargo.solarportal.in/" + doc.document_path;
            downloadLink.className = "btn btn-sm btn-outline-primary w-100";
            downloadLink.target = "_blank";
            downloadLink.innerHTML = '<i class="bi bi-arrows-fullscreen me-1"></i>View Full Size';
            cardBody.appendChild(downloadLink);
            
            cardDiv.appendChild(imageContainer);
            cardDiv.appendChild(cardBody);
            colDiv.appendChild(cardDiv);
            documentsContainer.appendChild(colDiv);
        });
    } else {
        documentsContainer.innerHTML = `
            <div class="col-12 empty-state">
                <i class="bi bi-file-earmark-x"></i>
                <h6 class="fw-semibold">No Documents Attached</h6>
                <p class="text-muted mb-0">No documents have been uploaded for this ticket yet.</p>
            </div>
        `;
    }

    // Display admin comments
    const adminCommentsContainer = document.getElementById('adminCommentsContainer');
    if (ticket.admin_comments && ticket.admin_comments.length > 0) {
        ticket.admin_comments.forEach(comment => {
            const commentDiv = document.createElement('div');
            commentDiv.className = "admin-comment";
            
            const commentHeader = document.createElement('div');
            commentHeader.className = "d-flex align-items-center mb-3";
            
            const adminIcon = document.createElement('div');
            adminIcon.className = "admin-avatar";
            adminIcon.innerHTML = '<i class="bi bi-person"></i>';
            
            const adminTitle = document.createElement('h6');
            adminTitle.className = "mb-0 fw-bold";
            adminTitle.textContent = "Admin Support";
            
            commentHeader.appendChild(adminIcon);
            commentHeader.appendChild(adminTitle);
            
            const commentText = document.createElement('p');
            commentText.className = "mb-3";
            commentText.textContent = comment.comment;
            
            const commentDate = document.createElement('div');
            commentDate.className = "d-flex align-items-center text-muted small";
            commentDate.innerHTML = `<i class="bi bi-clock me-1"></i>${new Date(comment.created_at).toLocaleDateString('en-US', options)}`;
            
            commentDiv.appendChild(commentHeader);
            commentDiv.appendChild(commentText);
            commentDiv.appendChild(commentDate);
            adminCommentsContainer.appendChild(commentDiv);
        });
    } else {
        adminCommentsContainer.innerHTML = `
            <div class="empty-state">
                <i class="bi bi-chat-square"></i>
                <h6 class="fw-semibold">No Feedback Yet</h6>
                <p class="text-muted mb-0">Our team will review your ticket and provide feedback soon.</p>
            </div>
        `;
    }

    // Handle form submission
    const uploadForm = document.getElementById('uploadForm');
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const fileInput = document.getElementById('documentFile');
        const file = fileInput.files[0];
        
        // Validate file size (max 1MB)
        if (file && file.size > 1024 * 1024) {
            alert("File size exceeds 1MB limit. Please select a smaller file.");
            return;
        }
        
        // Show loading spinner
        const loadingSpinner = document.getElementById('loadingSpinner');
        const submitButton = document.getElementById('submitBtn');
        loadingSpinner.classList.remove('d-none');
        submitButton.disabled = true;
        
        const formData = new FormData(uploadForm);
        
        fetch('https://backend.evargo.solarportal.in/evargo/api/v1/agent/update-ticket', {
            method: 'POST',
            body: formData,
            headers: {
                'Authorization': 'Bearer ' + sessionStorage.getItem('access_token')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Refresh the page to show updated data
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while uploading the document. Please try again.');
        })
        .finally(() => {
            // Hide loading spinner
            loadingSpinner.classList.add('d-none');
            submitButton.disabled = false;
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>