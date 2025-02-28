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
    <title>Support Ticket System | EVargo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        :root {
            --primary-color: #A945BB;         /* Main brand purple */
            --primary-dark: #8A35A3;          /* Darker purple for hover/gradient */
            --accent-color: #FFD30F;          /* Yellow accent */
            --dark-color: #393939;            /* Dark gray */
            --light-bg: #f8f5fa;              /* Light purple-tinted background */
            --white-color: #ffffff;           /* White */
            --medium-text: #6c757d;           /* Medium gray text */
            --border-radius: 12px;
            --box-shadow: 0 8px 30px rgba(169, 69, 187, 0.1), 0 5px 15px rgba(0, 0, 0, 0.05);
            --hover-shadow: 0 12px 25px rgba(169, 69, 187, 0.2);
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--dark-color);
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .page-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            background: var(--white-color);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-bottom: none;
            padding: 25px 30px;
            position: relative;
            overflow: hidden;
        }
        
        .card-header::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }
        
        .card-body {
            padding: 35px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px solid #e0d5e5;
            background-color: #f8f9fa;
            transition: all 0.2s ease;
            font-size: 16px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(169, 69, 187, 0.1);
        }
        
        textarea.form-control {
            min-height: 140px;
        }
        
        .input-group {
            margin-bottom: 10px;
        }
        
        .input-group-text {
            background-color: var(--primary-color);
            color: white;
            border: none;
            font-weight: 500;
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 8px;
            padding: 14px 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(169, 69, 187, 0.2);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(169, 69, 187, 0.3);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .img-preview-container {
            position: relative;
            margin-top: 15px;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .img-preview {
            display: none;
            max-width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .remove-image {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(57, 57, 57, 0.7);
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .img-preview-container:hover .remove-image {
            opacity: 1;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark-color);
            font-size: 15px;
        }
        
        .form-text {
            color: #7c7c7c;
            font-size: 13px;
            margin-top: 6px;
        }
        
        .toast {
            border-radius: 8px;
            box-shadow: var(--box-shadow);
        }
        
        /* Improved Loading Styles */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(57, 57, 57, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }
        
        .loading-content {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 90%;
            width: 300px;
            border-top: 4px solid var(--primary-color);
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }
        
        .loading-text {
            font-weight: 600;
            color: var(--dark-color);
            margin-top: 5px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .page-title {
            margin-bottom: 25px;
            color: var(--dark-color);
            font-weight: 700;
            text-align: center;
        }
        
        .card-icon {
            font-size: 24px;
            margin-right: 12px;
        }
        
        /* Word counter styles */
        .word-counter {
            display: flex;
            justify-content: flex-end;
            font-size: 0.85rem;
            color: #7c7c7c;
            margin-top: 5px;
        }
        
        .word-counter.error {
            color: #dc3545;
            font-weight: 600;
        }
        
        .word-counter.success {
            color: #198754;
            font-weight: 600;
        }
        
        /* Modal Dialog Styles */
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--hover-shadow);
        }
        
        .modal-success .modal-header {
            background-color: var(--accent-color);
            color: var(--dark-color);
            border-bottom: none;
        }
        
        .modal-error .modal-header {
            background-color: #dc3545;
            color: white;
            border-bottom: none;
        }
        
        .modal-icon {
            font-size: 64px;
            display: block;
            margin: 15px auto;
            text-align: center;
        }
        
        .modal-success .modal-icon {
            color: var(--accent-color);
        }
        
        .modal-error .modal-icon {
            color: #dc3545;
        }
        
        .modal-body {
            padding: 30px;
            text-align: center;
        }
        
        .modal-footer {
            border-top: none;
            justify-content: center;
            padding-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .page-container {
                margin: 20px auto;
                padding: 0 15px;
            }
            
            .card-body {
                padding: 25px 20px;
            }
            
            .card-header {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="page-container">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-headset card-icon"></i>
                    Support Center
                </h2>
                <p class="text-white-50 mb-0 mt-2">Submit a new support request</p>
            </div>
            
            <div class="card-body">
                <form id="ticketForm">
                    <div class="mb-4">
                        <label for="comment" class="form-label">
                            <i class="bi bi-pencil-square me-2"></i>Issue Description
                        </label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Please describe your issue in detail (minimum 50 words required)..." required></textarea>
                        <div id="wordCounter" class="word-counter">0 words (minimum 50 required)</div>
                    </div>

                    <div class="mb-4">
                        <label for="file" class="form-label">
                            <i class="bi bi-card-image me-2"></i>Evidence Image
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="file" name="file" accept="image/*">
                            <span class="input-group-text">Upload</span>
                        </div>
                        <div class="form-text">Supported formats: JPG, PNG, GIF (Max: 1MB)</div>
                        
                        <div class="img-preview-container">
                            <img id="preview" class="img-preview">
                            <div class="remove-image" id="removeImage">
                                <i class="bi bi-x"></i>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                            <i class="bi bi-send-check me-2"></i>Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">Processing your request...</div>
            <div class="mt-2 text-muted" style="font-size: 0.9rem;">Please wait a moment</div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
        <div id="toast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-success">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="successModalLabel">Request Submitted</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <i class="bi bi-check-circle-fill modal-icon"></i>
                    <h4 class="mb-3">Thank You!</h4>
                    <p>Your support request has been successfully submitted. Our team will review your issue and respond promptly.</p>
                    <p class="mb-0 mt-3 fw-semibold">Ticket ID: <span id="ticketIdDisplay"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="viewTicketBtn">View Ticket Details</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-error">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="errorModalLabel">Submission Error</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <i class="bi bi-exclamation-triangle-fill modal-icon"></i>
                    <h4 class="mb-3">Unable to Submit Request</h4>
                    <p id="errorModalMessage">There was an error processing your request. Please try again later.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="tryAgainBtn">Try Again</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Bootstrap components
        let toast, successModal, errorModal;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize toast
            toast = new bootstrap.Toast(document.getElementById('toast'), {
                delay: 5000,
                animation: true
            });
            
            // Initialize modals
            successModal = new bootstrap.Modal(document.getElementById('successModal'));
            errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            
            // Word counter functionality
            const commentInput = document.getElementById('comment');
            const wordCounter = document.getElementById('wordCounter');
            const submitBtn = document.getElementById('submitBtn');
            
            commentInput.addEventListener('input', function() {
                // Count words (split by whitespace and filter out empty strings)
                const words = this.value.trim().split(/\s+/).filter(word => word.length > 0);
                const wordCount = words.length;
                
                // Update counter
                wordCounter.textContent = `${wordCount} word${wordCount !== 1 ? 's' : ''} (minimum 50 required)`;
                
                // Update counter styling based on word count
                if (wordCount < 50) {
                    wordCounter.className = 'word-counter error';
                    submitBtn.disabled = true;
                } else {
                    wordCounter.className = 'word-counter success';
                    submitBtn.disabled = false;
                }
            });
            
            // Modal button handlers
            document.getElementById('viewTicketBtn').addEventListener('click', function() {
                window.location.href = 'ticket_details.php';
            });
            
            document.getElementById('tryAgainBtn').addEventListener('click', function() {
                errorModal.hide();
            });
        });

        // Handle file preview
        document.getElementById('file').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');

            if (file) {
                if (!file.type.startsWith('image/')) {
                    showToast('Please select an image file', 'danger');
                    event.target.value = '';
                    preview.style.display = 'none';
                    return;
                }
                if (file.size > 1 * 1024 * 1024) {
                    showToast('Image must be smaller than 1MB', 'danger');
                    event.target.value = '';
                    preview.style.display = 'none';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Remove image button
        document.getElementById('removeImage').addEventListener('click', function() {
            document.getElementById('file').value = '';
            document.getElementById('preview').style.display = 'none';
        });

        // Form submission
        document.getElementById('ticketForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Validate word count again
            const commentText = document.getElementById('comment').value.trim();
            const wordCount = commentText.split(/\s+/).filter(word => word.length > 0).length;
            
            if (wordCount < 50) {
                showToast('Please provide at least 50 words in your description', 'danger');
                return;
            }
            
            // Show loading overlay
            document.getElementById('loadingOverlay').style.display = 'flex';
            
            // Disable submit button to prevent multiple submissions
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;

            const formData = new FormData();
            formData.append('comment', commentText);
            
            // Only append file if one is selected
            const fileInput = document.getElementById('file');
            if (fileInput.files.length > 0) {
                formData.append('file', fileInput.files[0]);
            }

            axios.post('https://backend.evargo.solarportal.in/evargo/api/v1/agent/raise-ticket', formData, {
                    headers: {
                        'Authorization': 'Bearer ' + sessionStorage.getItem('access_token')
                    }
                })
                .then(response => {
                    console.log('Response:', response.data);
                    
                    // Check if response is successful
                    if (response.data && response.data.status === 'success') {
                        // Store ticket data in sessionStorage
                        sessionStorage.setItem('ticketData', JSON.stringify(response.data.data));
                        
                        // Display ticket ID in success modal
                        if (response.data.data && response.data.data.ticket_id) {
                            document.getElementById('ticketIdDisplay').textContent = response.data.data.ticket_id;
                        } else {
                            document.getElementById('ticketIdDisplay').textContent = 'Generated';
                        }
                        
                        // Reset form
                        document.getElementById('ticketForm').reset();
                        document.getElementById('preview').style.display = 'none';
                        document.getElementById('wordCounter').textContent = '0 words (minimum 50 required)';
                        document.getElementById('wordCounter').className = 'word-counter error';
                        
                        // Show success modal
                        successModal.show();
                    } else {
                        // Show error message in modal
                        document.getElementById('errorModalMessage').textContent = 
                            'Request processed but returned an unexpected format. Please try again or contact support.';
                        errorModal.show();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = 'Unable to process your request. Please try again.';
                    
                    if (error.response && error.response.data && error.response.data.message) {
                        errorMessage = error.response.data.message;
                    }
                    
                    // Show error message in modal
                    document.getElementById('errorModalMessage').textContent = errorMessage;
                    errorModal.show();
                })
                .finally(() => {
                    // Hide loading overlay and re-enable button after a slight delay
                    setTimeout(() => {
                        document.getElementById('loadingOverlay').style.display = 'none';
                        submitButton.disabled = (wordCount < 50);
                    }, 500);
                });
        });

        // Toast notification function
        function showToast(message, type) {
            const toastElement = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            toastMessage.textContent = message;
            toastElement.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info');
            
            if (type === 'danger') {
                toastElement.classList.add('bg-danger');
            } else if (type === 'success') {
                toastElement.classList.add('bg-success');
            } else if (type === 'warning') {
                toastElement.classList.add('bg-warning');
                toastMessage.style.color = '#212529';
            } else {
                toastElement.classList.add('bg-primary');
            }

            // Create a new Bootstrap Toast instance if not already done
            if (!toast) {
                toast = new bootstrap.Toast(toastElement);
            }
            
            toast.show();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>