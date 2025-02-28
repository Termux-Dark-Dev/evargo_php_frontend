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
    <title>Create Project</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Toastify JS for toast notifications -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        :root {
            --primary: #A945BB;
            --primary-light: #C679D3;
            --primary-dark: #8A3696;
            --secondary: #FFD30F;
            --secondary-light: #FFE159;
            --secondary-dark: #E3BB00;
            --dark: #393939;
            --dark-light: #5A5A5A;
            --white: #FFFFFF;
            --light-bg: #F8F9FA;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            border: none;
            overflow: hidden;
        }
        
        .card-header {
            background-color: var(--primary);
            color: var(--white);
            border-radius: 15px 15px 0 0 !important;
            padding: 24px;
            position: relative;
        }
        
        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 150px;
            height: 60px;
            background-color: var(--secondary);
            opacity: 0.3;
            border-top-left-radius: 100%;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .form-label {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            padding: 14px;
            border: 2px solid #e2e2e2;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(169, 69, 187, 0.25);
            border-color: var(--primary-light);
        }
        
        .input-group-text {
            background-color: var(--secondary);
            color: var(--dark);
            font-weight: bold;
            border-radius: 10px 0 0 10px;
            border: 2px solid var(--secondary);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 14px 24px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(169, 69, 187, 0.3);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(169, 69, 187, 0.4);
        }
        
        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
            border-radius: 8px;
        }
        
        .file-upload {
            position: relative;
            overflow: hidden;
            margin-top: 10px;
            cursor: pointer;
        }
        
        .file-upload input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            cursor: pointer;
            display: block;
        }
        
        .custom-file-upload {
            display: block;
            border: 2px dashed #d1d1d1;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s;
            background-color: rgba(169, 69, 187, 0.05);
        }
        
        .custom-file-upload:hover {
            border-color: var(--primary);
            background-color: rgba(169, 69, 187, 0.1);
        }
        
        .custom-file-upload i {
            color: var(--primary);
        }
        
        .file-preview {
            display: none;
            margin-top: 15px;
            background-color: rgba(255, 211, 15, 0.1);
            padding: 15px;
            border-radius: 12px;
            border: 1px solid rgba(255, 211, 15, 0.3);
        }
        
        .file-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .file-error {
            color: #dc3545;
            margin-top: 12px;
            padding: 10px 15px;
            border-radius: 8px;
            background-color: rgba(220, 53, 69, 0.1);
            display: none;
        }
        
        #removeImage {
            background-color: white;
            color: #dc3545;
            border: 1px solid #dc3545;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        
        #removeImage:hover {
            background-color: #dc3545;
            color: white;
        }
        
        .mb-4 {
            margin-bottom: 1.8rem !important;
        }
        
        /* Stylish input focus effect */
        .form-floating {
            position: relative;
        }
        
        /* Section title */
        .section-title {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 25px;
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            height: 3px;
            width: 50px;
            background-color: var(--secondary);
        }
        
        /* Card hover effect */
        .card {
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
<?php
// Include the navbar
include 'navbar.php';
?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Create New Project</h4>
                    </div>
                    <div class="card-body p-4">
                        <form id="createProjectForm">
                            <div class="mb-4">
                                <label for="sale_desc" class="form-label">Sales Description</label>
                                <textarea class="form-control" id="sale_desc" name="sale_desc" rows="3" placeholder="Enter a detailed description of the project" required></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="sale_amount" class="form-label">Sales Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="sale_amount" name="sale_amount" placeholder="Enter sales amount" step="0.01" min="0" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="sale_date" class="form-label">Sale Date</label>
                                <input type="date" class="form-control" id="sale_date" name="sale_date" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Project Image</label>
                                <div class="file-upload">
                                    <div class="custom-file-upload" id="fileUploadContainer">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                        <h5>Drag and drop an image or click to browse</h5>
                                        <p class="text-muted">Supported formats: JPG, PNG, GIF (Max size: 1MB)</p>
                                    </div>
                                    <input type="file" id="file" name="file" accept="image/*" required>
                                </div>
                                <div class="file-error" id="fileError">
                                    <i class="fas fa-exclamation-circle me-1"></i> 
                                    Image size exceeds the 1MB limit. Please choose a smaller image.
                                </div>
                                <div class="file-preview" id="filePreview">
                                    <img id="imagePreview" src="#" alt="Image Preview">
                                    <div class="mt-3 text-center">
                                        <button type="button" class="btn btn-sm" id="removeImage">
                                            <i class="fas fa-times me-1"></i> Remove Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>Create Project
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toastify JS -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('sale_date').value = today;
            
            // Image preview functionality
            const fileInput = document.getElementById('file');
            const filePreview = document.getElementById('filePreview');
            const imagePreview = document.getElementById('imagePreview');
            const fileUploadContainer = document.getElementById('fileUploadContainer');
            const removeImageBtn = document.getElementById('removeImage');
            const fileError = document.getElementById('fileError');
            
            // Define the maximum file size (1MB = 1,048,576 bytes)
            const maxFileSize = 1048576;
            
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    
                    // Check file size
                    if (file.size > maxFileSize) {
                        fileError.style.display = 'block';
                        filePreview.style.display = 'none';
                        fileUploadContainer.style.display = 'block';
                        this.value = ''; // Clear the file input
                        return;
                    }
                    
                    fileError.style.display = 'none';
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        filePreview.style.display = 'block';
                        fileUploadContainer.style.display = 'none';
                    }
                    
                    reader.readAsDataURL(file);
                }
            });
            
            removeImageBtn.addEventListener('click', function() {
                fileInput.value = '';
                filePreview.style.display = 'none';
                fileUploadContainer.style.display = 'block';
                fileError.style.display = 'none';
            });
            
            // Form submission
            const form = document.getElementById('createProjectForm');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Check if file is selected and valid
                if (fileInput.files.length === 0) {
                    Toastify({
                        text: "Please select an image file",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#dc3545",
                    }).showToast();
                    return;
                }
                
                if (fileInput.files[0].size > maxFileSize) {
                    fileError.style.display = 'block';
                    return;
                }
                
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Creating...';
                
                const formData = new FormData();
                formData.append('sale_desc', document.getElementById('sale_desc').value);
                formData.append('sale_amount', document.getElementById('sale_amount').value);
                formData.append('sale_date', document.getElementById('sale_date').value);
                formData.append('file', fileInput.files[0]);
                
                fetch('https://backend.evargo.solarportal.in/evargo/api/v1/agent/create-project', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Authorization': 'Bearer ' + sessionStorage.getItem('access_token')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Toastify({
                            text: "Project created successfully!",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#A945BB", // Using primary brand color
                        }).showToast();
                        
                        // Reset form
                        form.reset();
                        document.getElementById('sale_date').value = today;
                        filePreview.style.display = 'none';
                        fileUploadContainer.style.display = 'block';
                        fileError.style.display = 'none';
                        
                        // Redirect after success (optional)
                        // window.location.href = 'projects.php';
                    } else {
                        throw new Error(data.message || 'Failed to create project');
                    }
                })
                .catch(error => {
                    Toastify({
                        text: error.message || "An error occurred. Please try again.",
                        duration: 5000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#dc3545",
                    }).showToast();
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Create Project';
                });
            });
        });
    </script>
</body>
</html>