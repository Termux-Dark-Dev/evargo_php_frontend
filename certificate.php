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
    <title>Certificate | EVargo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- jsPDF library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        

        

        /* Certificate Page Styles */
        .page-container {
            padding: 2.5rem 1rem;
        }

        .certificate-container {
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

        .certificate-preview {
            width: 100%;
            margin: 0 auto;
            position: relative;
            text-align: center;
        }

        #certificate-canvas {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            display: block;
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

        .alert {
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: none;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .user-infoX {
            background-color: rgba(169, 69, 187, 0.05);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-color);
        }

        .user-infoX h4 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .user-infoX p {
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .user-infoX .label {
            font-weight: 600;
            color: var(--dark-color);
        }

        .user-infoX .value {
            font-weight: 500;
            color: var(--primary-color);
        }

        .download-info {
            background-color: rgba(255, 211, 15, 0.1);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-top: 1.5rem;
            border-left: 4px solid var(--accent-color);
        }

        .download-info i {
            color: var(--accent-color);
            font-size: 1.5rem;
            margin-right: 1rem;
        }

        
    </style>
</head>

<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Page Content -->
    <div class="container page-container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Alert Placeholder for Messages -->
                <div id="alertPlaceholder"></div>

                <div class="certificate-container">
                    <h2 class="page-title">Your Certificate</h2>

                    <!-- User Info Section -->
                    <div class="user-infoX" id="userInfoSection">
                        <h4><i class="bi bi-person-badge"></i> Certificate Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p><span class="label">Name:</span> <span class="value" id="userNameDisplay">Loading...</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><span class="label">Role:</span> <span class="value" id="userRoleDisplay">Loading...</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Preview -->
                    <div class="certificate-preview">
                        <canvas id="certificate-canvas"></canvas>
                    </div>

                    <!-- Download Info -->
                    <div class="download-info">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle"></i>
                            <div>
                                <h5 class="mb-1">Download Your Certificate</h5>
                                <p class="mb-0">Click the button below to download your certificate as a PDF file.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Download Button -->
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-primary btn-lg" id="downloadBtn">
                            <i class="bi bi-download me-2"></i>Download Certificate
                        </button>
                    </div>
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
            <p class="mb-0 mt-3 fw-bold">Generating your certificate...</p>
            <p class="text-muted small mt-2">Please wait a moment</p>
        </div>
    </div>

    <!-- Template Image (hidden) -->
    <img src="assets/png/certificate-template.jpg" alt="Certificate Template" id="certificateTemplate" style="display: none;" crossorigin="Anonymous">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Axios for API Calls -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Import jsPDF
        const { jsPDF } = window.jspdf;

        document.addEventListener('DOMContentLoaded', function() {
            // Main elements
            const loadingSpinner = document.getElementById('loadingSpinner');
            const alertPlaceholder = document.getElementById('alertPlaceholder');
            const userNameDisplay = document.getElementById('userNameDisplay');
            const userRoleDisplay = document.getElementById('userRoleDisplay');
            const canvas = document.getElementById('certificate-canvas');
            const ctx = canvas.getContext('2d');
            const downloadBtn = document.getElementById('downloadBtn');
            const certificateTemplate = document.getElementById('certificateTemplate');

            // User data
            let userData = {
                name: '',
                role: ''
            };

            // Text coordinates and sizes
            const textConfig = {
                name: {
                    x: 650, // center of canvas
                    y: 460, // position for name
                    fontSize: 60,
                    fontFamily: "'Poppins', sans-serif",
                    color: '#34407d',
                    weight: 'bold'
                },
                role: {
                    x: 650, // center of canvas
                    y: 260, // position for role
                    maxWidth: 520, // max width for role text box
                    fontSize: 42,
                    fontFamily: "'Poppins', sans-serif",
                    color: '#FFFFFF',
                    weight: 'bold'
                },
                partnerTitle: {
                    x:650,
                    y: 570,
                    maxWidth: 900,
                    fontSize: 66,
                    fontFamily: "'Poppins', sans-serif",
                    color: '#000000',
                    weight: 'bold'
                }
            };

            // Show loading spinner
            function showLoading() {
                loadingSpinner.classList.add('show');
            }

            // Hide loading spinner
            function hideLoading() {
                loadingSpinner.classList.remove('show');
            }

            // Show alert message
            function showAlert(message, type) {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = `
                    <div class="alert alert-${type} d-flex align-items-center" role="alert">
                        <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'} me-2"></i>
                        <div>${message}</div>
                    </div>
                `;

                alertPlaceholder.innerHTML = '';
                alertPlaceholder.appendChild(wrapper);

                // Auto-dismiss alert after 5 seconds
                setTimeout(() => {
                    wrapper.querySelector('.alert').classList.add('fade');
                    setTimeout(() => {
                        alertPlaceholder.innerHTML = '';
                    }, 500);
                }, 5000);
            }

            // Format role text for display
            function formatRoleText(role) {
                let displayRole = role;
                switch (role) {
                    case 'ChannelPartner':
                        displayRole = 'Channel Partner';
                        break;
                    case 'MasterDistributor':
                        displayRole = 'Master Distributor';
                        break;
                    case 'Distributor':
                        displayRole = 'Distributor';
                        break;
                    case 'Retailer':
                        displayRole = 'Retailer';
                        break;
                }
                return `Certificate of ${displayRole}`;
            }

            // Calculate font size to fit text in a box
            function calculateFontSize(text, maxWidth, fontSize, fontFamily, fontWeight) {
                ctx.font = `${fontWeight} ${fontSize}px ${fontFamily}`;
                let textWidth = ctx.measureText(text).width;
                
                // If text fits, return the original font size
                if (textWidth <= maxWidth) {
                    return fontSize;
                }
                
                // Calculate the right font size
                const ratio = maxWidth / textWidth;
                return Math.floor(fontSize * ratio);
            }

            // Draw text centered in a specific position with auto-fit
            function drawCenteredText(text, x, y, maxWidth, fontSize, fontFamily, color, weight) {
                // Calculate the appropriate font size
                const fitFontSize = calculateFontSize(text, maxWidth, fontSize, fontFamily, weight);
                
                // Set font properties
                ctx.font = `${weight} ${fitFontSize}px ${fontFamily}`;
                ctx.fillStyle = color;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                
                // Draw the text
                ctx.fillText(text, x, y);
                
                return fitFontSize; // Return the actual font size used
            }

            // Render certificate on canvas
            function renderCertificate() {
                // Set canvas dimensions to match template image
                canvas.width = certificateTemplate.naturalWidth;
                canvas.height = certificateTemplate.naturalHeight;
                
                // Draw background image
                ctx.drawImage(certificateTemplate, 0, 0, canvas.width, canvas.height);
                
                // Draw name
                drawCenteredText(
                    userData.name,
                    textConfig.name.x,
                    textConfig.name.y,
                    canvas.width * 0.7, // 70% of canvas width as max width
                    textConfig.name.fontSize,
                    textConfig.name.fontFamily,
                    textConfig.name.color,
                    textConfig.name.weight
                );
                
                // Draw role with "Certificate of" prefix
                const roleText = formatRoleText(userData.role);
                drawCenteredText(
                    roleText,
                    textConfig.role.x,
                    textConfig.role.y,
                    textConfig.role.maxWidth,
                    textConfig.role.fontSize,
                    textConfig.role.fontFamily,
                    textConfig.role.color,
                    textConfig.role.weight
                );

                drawCenteredText(
                    `is appointed as an authorized ${userData.role} of Evargo Services.`,
                    textConfig.partnerTitle.x,
                    textConfig.partnerTitle.y,
                    textConfig.partnerTitle.maxWidth,
                    textConfig.partnerTitle.fontSize,
                    textConfig.partnerTitle.fontFamily,
                    textConfig.partnerTitle.color,
                    textConfig.partnerTitle.weight
                );
                
                hideLoading();
            }

            // Download certificate as PDF
            function downloadCertificate() {
                showLoading();
                
                try {
                    // Create new PDF with landscape orientation
                    const pdf = new jsPDF({
                        orientation: 'landscape',
                        unit: 'mm',
                        format: 'a4'
                    });
                    
                    // Get canvas data and add to PDF
                    const imgData = canvas.toDataURL('image/jpeg', 1.0);
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = pdf.internal.pageSize.getHeight();
                    
                    pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);
                    
                    // Save PDF
                    pdf.save(`EVargo_Certificate_${userData.name.replace(/\s+/g, '_')}.pdf`);
                    
                    hideLoading();
                    showAlert('Certificate downloaded successfully!', 'success');
                } catch (e) {
                    console.error('PDF generation error:', e);
                    hideLoading();
                    showAlert('There was an error generating the PDF. Please try again.', 'danger');
                }
            }

            // Fetch user data from API
            function fetchUserData() {
                showLoading();

                // Get role from session
                const role = "<?php echo $role; ?>";

                if (!role) {
                    hideLoading();
                    showAlert('User role not found. Please login again.', 'danger');
                    return;
                }

                // Store role
                userData.role = role;
                userRoleDisplay.textContent = formatRoleText(role);

                // Call API to get user details
                axios.get('https://backend.evargo.solarportal.in/evargo/api/v1/agent/profile', {
                    headers: {
                        'Authorization': 'Bearer ' + sessionStorage.getItem('access_token')
                    }
                })
                .then(response => {
                    if (response.data.status === 'success') {
                        // Store name
                        userData.name = response.data.data.details.name;
                        userNameDisplay.textContent = userData.name;

                        // Initialize certificate
                        initCertificate();
                    } else {
                        throw new Error('Failed to fetch user data');
                    }
                })
                .catch(error => {
                    console.error('Error fetching user data:', error);
                    hideLoading();
                    showAlert('Failed to load user data. Please try again later.', 'danger');
                });
            }

            // Initialize certificate
            function initCertificate() {
                // Wait for image to load
                if (!certificateTemplate.complete) {
                    certificateTemplate.onload = renderCertificate;
                } else {
                    renderCertificate();
                }
            }

            // Event listeners
            downloadBtn.addEventListener('click', downloadCertificate);

            // Load certificate template and fetch user data
            certificateTemplate.onerror = function() {
                hideLoading();
                showAlert('Failed to load certificate template. Please refresh the page or contact support.', 'danger');
            };
            
            // Start fetching user data once the template starts loading
            if (certificateTemplate.complete) {
                fetchUserData();
            } else {
                certificateTemplate.onload = fetchUserData;
            }
        });
    </script>
</body>

</html>