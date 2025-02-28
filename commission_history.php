<!-- Error Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 8px; border-left: 4px solid #dc3545;">
      <div class="toast-header bg-danger text-white" style="border-radius: 8px 8px 0 0;">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong class="me-auto">Error</strong>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body" id="errorToastMessage">
        An error occurred. Please try again.
      </div>
    </div>
  </div><?php
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
  <title>Commission History | EVargo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    :root {
      --primary-color: #A945BB;         /* Main purple */
      --primary-dark: #8C38A3;          /* Darker purple */
      --primary-light: #C76AD4;         /* Lighter purple */
      --accent-color: #FFD30F;          /* Yellow accent */
      --dark-color: #393939;            /* Dark gray */
      --light-bg: #f8f5fa;              /* Light purple-tinted background */
      --white-color: #ffffff;           /* White */
      --primary-gradient: linear-gradient(135deg, #A945BB, #8C38A3);
      --card-shadow: 0 8px 20px rgba(169, 69, 187, 0.1);
      --hover-shadow: 0 12px 30px rgba(169, 69, 187, 0.15);
      --border-radius: 12px;
    }
    
    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--dark-color);
    }
    
    /* Card styling */
    .card {
      border-radius: var(--border-radius);
      border: none;
      box-shadow: var(--card-shadow);
      transition: all 0.3s ease;
    }
    
    .card:hover {
      box-shadow: var(--hover-shadow);
    }
    
    .card-header {
      border-bottom: 1px solid rgba(169, 69, 187, 0.1);
      font-weight: 600;
    }
    
    /* Summary cards */
    .summary-card {
      border-top: 4px solid var(--primary-color);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .summary-card:hover {
      transform: translateY(-5px);
    }
    
    /* Text colors */
    .text-primary {
      color: var(--primary-color) !important;
    }
    
    .text-accent {
      color: var(--accent-color) !important;
    }
    
    /* Button styling */
    .btn-primary {
      background: var(--primary-gradient);
      border: none;
      box-shadow: 0 4px 10px rgba(169, 69, 187, 0.2);
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(169, 69, 187, 0.3);
    }
    
    .btn-outline-primary {
      color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-success {
      background-color: #28a745;
      border: none;
      box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
    }
    
    .btn-success:hover {
      background-color: #218838;
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(40, 167, 69, 0.3);
    }
    
    .btn-outline-secondary {
      color: var(--dark-color);
      border-color: #ced4da;
      transition: all 0.3s ease;
    }
    
    .btn-outline-secondary:hover {
      background-color: rgba(169, 69, 187, 0.1);
      border-color: var(--primary-color);
      color: var(--primary-color);
    }
    
    /* Table styling */
    .table {
      margin-bottom: 0;
    }
    
    .table-hover tbody tr:hover {
      background-color: rgba(169, 69, 187, 0.05);
    }
    
    .table thead th {
      border-bottom: 2px solid rgba(169, 69, 187, 0.1);
      color: var(--primary-color);
      font-weight: 600;
    }
    
    /* Badge styling */
    .badge.bg-primary {
      background-color: rgba(169, 69, 187, 0.15) !important;
      color: var(--primary-color) !important;
      font-weight: 500;
    }
    
    /* Form controls */
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(169, 69, 187, 0.25);
    }
    
    /* Loading spinner */
    #globalLoading {
      background: rgba(248, 245, 250, 0.9) !important;
    }
    
    .spinner-border.text-primary {
      color: var(--primary-color) !important;
    }
    
    /* Empty state */
    #emptyState i.fas.fa-inbox {
      color: rgba(169, 69, 187, 0.3);
    }
    
    /* Toast styling */
    .toast {
      border-radius: 8px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    /* Modal styling */
    .modal-content {
      border-radius: 12px;
      border: none;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
      border-bottom: 1px solid rgba(169, 69, 187, 0.1);
    }
    
    .modal-footer {
      border-top: 1px solid rgba(169, 69, 187, 0.1);
    }
    
    .display-6.text-success {
      color: #28a745 !important;
      font-weight: 600;
    }
  </style>
</head>

<body>
  <!-- Global Loader -->
  <div id="globalLoading" style="position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(255,255,255,0.8); display: flex; align-items: center; justify-content: center; z-index: 9999;">
    <div class="spinner-border" style="width: 3rem; height: 3rem; color: var(--primary-color);" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <div style="height: 50px;"></div>
 
  <div class="container mb-5">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold" style="color: var(--primary-color);">Commission History</h3>
    </div>
  
    <!-- Summary Cards -->
    <div class="row mb-4">
      <div class="col-md-4 mb-3 mb-md-0">
        <div class="card shadow-sm h-100 summary-card">
          <div class="card-body text-center p-4">
            <div class="mb-3" style="width: 60px; height: 60px; background: rgba(169, 69, 187, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
              <i class="fas fa-coins" style="font-size: 24px; color: var(--primary-color);"></i>
            </div>
            <h5 class="card-title text-muted">Total Commission</h5>
            <h2 id="totalCommission" class="card-text" style="color: var(--primary-color); font-weight: 700;">₹0</h2>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3 mb-md-0">
        <div class="card shadow-sm h-100 summary-card" style="border-top: 4px solid #28a745;">
          <div class="card-body text-center p-4">
            <div class="mb-3" style="width: 60px; height: 60px; background: rgba(40, 167, 69, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
              <i class="fas fa-wallet" style="font-size: 24px; color: #28a745;"></i>
            </div>
            <h5 class="card-title text-muted">Withdrawable Amount</h5>
            <h2 id="withdrawableAmount" class="card-text text-success mb-3" style="font-weight: 700;">₹0</h2>
            <button id="withdrawBtn" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#withdrawModal" style="border-radius: 8px; padding: 8px 16px; font-weight: 500;">
              <i class="fas fa-wallet me-2"></i>Withdraw Funds
            </button>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm h-100 summary-card" style="border-top: 4px solid var(--accent-color);">
          <div class="card-body text-center p-4">
            <div class="mb-3" style="width: 60px; height: 60px; background: rgba(255, 211, 15, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
              <i class="fas fa-exchange-alt" style="font-size: 24px; color: var(--accent-color);"></i>
            </div>
            <h5 class="card-title text-muted">Total Transactions</h5>
            <h2 id="transactionCount" class="card-text" style="color: var(--accent-color); font-weight: 700;">0</h2>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-3 mb-md-0">
            <label for="dateFilter" class="form-label fw-semibold" style="color: var(--dark-color);">Filter by Date</label>
            <input type="date" id="dateFilter" class="form-control" style="border-radius: 8px; padding: 10px 15px; border: 1px solid #e0d5e5;">
          </div>
          <div class="col-md-6 d-flex align-items-end">
            <button id="clearFilter" class="btn btn-outline-secondary ms-auto" style="border-radius: 8px; padding: 10px 20px;">
              <i class="fas fa-times me-2"></i>Clear Filter
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <strong style="color: var(--primary-color);"><i class="fas fa-chart-line me-2"></i>Commission Trends</strong>
            <button id="refreshCharts" class="btn btn-sm btn-outline-primary" style="border-radius: 6px;">
              <i class="fas fa-sync-alt"></i>
            </button>
          </div>
          <div class="card-body">
            <div style="height: 300px">
              <canvas id="trendChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Commission Table -->
    <div class="card shadow-sm">
      <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <strong style="color: var(--primary-color);"><i class="fas fa-list-alt me-2"></i>Commission Details</strong>
        <div>
          <button id="exportCSV" class="btn btn-sm btn-outline-primary me-2" style="border-radius: 6px;">
            <i class="fas fa-file-csv me-1"></i> Export
          </button>
          <button id="refreshTable" class="btn btn-sm btn-outline-primary" style="border-radius: 6px;">
            <i class="fas fa-sync-alt"></i>
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead style="background-color: rgba(169, 69, 187, 0.05);">
              <tr>
                <th style="padding: 15px 16px;">Amount</th>
                <th style="padding: 15px 16px;">Retailer</th>
                <th style="padding: 15px 16px;">Date</th>
                <th style="padding: 15px 16px;">Description</th>
              </tr>
            </thead>
            <tbody id="commissionTable">
              <!-- Skeleton Loader Rows -->
              <tr>
                <td colspan="4" class="text-center py-5">
                  <div class="spinner-border" style="width: 2rem; height: 2rem; color: var(--primary-color);" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                  <p class="mt-2 text-muted">Loading data...</p>
                </td>
              </tr>
            </tbody>
          </table>
          <!-- Empty State -->
          <div id="emptyState" class="d-none text-center py-5">
            <i class="fas fa-inbox fs-1 text-muted mb-3 d-block"></i>
            <h5 style="color: var(--primary-color);">No Commission Records Found</h5>
            <p class="text-muted small">Your commission history will appear here once available.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modified Withdrawal Modal - Now a Confirmation Modal -->
  <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to right, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05));">
          <h5 class="modal-title" id="withdrawModalLabel" style="color: #28a745; font-weight: 600;"><i class="fas fa-wallet me-2"></i>Confirm Withdrawal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-4">
            <div style="width: 80px; height: 80px; background: rgba(40, 167, 69, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
              <i class="fas fa-money-bill-wave" style="font-size: 32px; color: #28a745;"></i>
            </div>
            <div class="display-6 text-success mb-3" style="font-weight: 700;">₹<span id="modalAvailableAmount">0</span></div>
            <p class="lead" style="color: var(--dark-color);">Withdrawable Amount</p>
          </div>
          <div class="alert alert-info" style="border-radius: 8px; background-color: rgba(13, 202, 240, 0.1); border-left: 4px solid #0dcaf0; border-top: 0; border-right: 0; border-bottom: 0;">
            <i class="fas fa-info-circle me-2"></i>
            Withdrawal requests are typically processed within 2-3 business days.
          </div>
          <p class="mb-0" style="color: var(--dark-color);">Do you want to withdraw this amount?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 8px 20px;">Cancel</button>
          <button type="button" id="confirmWithdraw" class="btn btn-success" style="border-radius: 8px; padding: 8px 20px; font-weight: 500;">
            <i class="fas fa-check me-2"></i>Confirm Withdrawal
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Success Dialog -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white" style="border-radius: 12px 12px 0 0;">
          <h5 class="modal-title" id="successModalLabel"><i class="fas fa-check-circle me-2"></i>Success</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center py-4">
          <div style="width: 80px; height: 80px; background: rgba(40, 167, 69, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
            <i class="fas fa-check" style="font-size: 40px; color: #28a745;"></i>
          </div>
          <h4 class="mb-3" style="color: #28a745; font-weight: 600;">Withdrawal Successful!</h4>
          <p>Your withdrawal request has been submitted successfully.</p>
          <p class="text-muted mb-0">You will receive the amount in your registered account within 2-3 business days.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success px-4" id="successCloseBtn" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Error Dialog -->
  <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white" style="border-radius: 12px 12px 0 0;">
          <h5 class="modal-title" id="errorModalLabel"><i class="fas fa-exclamation-circle me-2"></i>Error</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center py-4">
          <div style="width: 80px; height: 80px; background: rgba(220, 53, 69, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
            <i class="fas fa-times" style="font-size: 40px; color: #dc3545;"></i>
          </div>
          <h4 class="mb-3" style="color: #dc3545; font-weight: 600;">Withdrawal Failed</h4>
          <p id="errorModalMessage">An error occurred while processing your withdrawal request.</p>
          <p class="text-muted mb-0">Please try again or contact support if the issue persists.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", function () {
    // Keep all existing code, but modify the requestWithdrawal function and related parts
    
    let commissionsLoaded = false;
    let dashboardLoaded = false;
    
    function checkAllLoaded() {
      if (commissionsLoaded && dashboardLoaded) {
        console.log('ghere');
        document.getElementById("globalLoading").style.display = "none";
      }
    }
    
    const commissionApiUrl = "https://backend.evargo.solarportal.in/evargo/api/v1/agent/commission-history";
    const dashboardApiUrl = "https://backend.evargo.solarportal.in/evargo/api/v1/agent/dashboard";
    const withdrawalApiUrl = "https://backend.evargo.solarportal.in/evargo/api/v1/agent/request-withdrawal";
    
    let commissionData = [];
    let dashboardData = {};
    let trendChart = null;
    let withdrawableAmount = 0;
    
    // Initialize both toasts and modal dialogs
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
    
    function showErrorToast(message) {
      document.getElementById('errorToastMessage').textContent = message;
      errorToast.show();
    }
    
    function showErrorDialog(message) {
      document.getElementById('errorModalMessage').textContent = message;
      errorModal.show();
    }
    
    function formatDate(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString.replace(/(\d+)\/(\d+)\/(\d+)/, '$3-$2-$1'));
      if (isNaN(date.getTime())) return dateString;
      return date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
      });
    }
    
    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
    function fetchDashboardData() {
      axios.get(dashboardApiUrl, {
        headers: {
          'Authorization': 'Bearer ' + sessionStorage.getItem('access_token')
        }
      })
      .then(response => {
        if (response.data.status === "success") {
          dashboardData = response.data.data;
          withdrawableAmount = dashboardData.withdrawable_amount || 0;
          document.getElementById("withdrawableAmount").textContent = `₹${numberWithCommas(withdrawableAmount)}`;
          document.getElementById("modalAvailableAmount").textContent = `${numberWithCommas(withdrawableAmount)}`;
          document.getElementById("withdrawBtn").disabled = withdrawableAmount <= 0;
          
          // Update the button text if disabled
          if (withdrawableAmount <= 0) {
            document.getElementById("withdrawBtn").innerHTML = '<i class="fas fa-wallet me-2"></i>No Funds Available';
          }
        }
      })
      .catch(error => {
        console.error("Error fetching dashboard data:", error);
        showErrorToast("Failed to fetch withdrawable amount");
      })
      .finally(() => {
        dashboardLoaded = true;
        checkAllLoaded();
      });
    }
    
    function fetchCommissions() {
      axios.get(commissionApiUrl, {
        headers: {
          'Authorization': 'Bearer ' + sessionStorage.getItem('access_token')
        }
      })
      .then(response => {
        if (response.data.status === "success") {
          commissionData = response.data.data.details || [];
          const totalAmount = response.data.data.total || 0;
          document.getElementById("totalCommission").textContent = `₹${numberWithCommas(totalAmount)}`;
          document.getElementById("transactionCount").textContent = commissionData.length;
          displayCommissions(commissionData);
          createTrendChart(commissionData);
        } else {
          handleError("Failed to fetch commission data");
        }
      })
      .catch(error => {
        console.error("Error fetching data:", error);
        handleError("Error connecting to the server");
      })
      .finally(() => {
        commissionsLoaded = true;
        checkAllLoaded();
      });
    }
    
    function displayCommissions(data) {
      let tableBody = document.getElementById("commissionTable");
      const emptyState = document.getElementById("emptyState");
      tableBody.innerHTML = "";
      if (data.length === 0) {
        emptyState.classList.remove('d-none');
        return;
      }
      emptyState.classList.add('d-none');
      data.forEach(item => {
        const formattedDate = formatDate(item.sale_date);
        let row = document.createElement('tr');
        row.innerHTML = `
          <td class="fw-bold text-success" style="padding: 15px 16px;">₹${numberWithCommas(item.amount)}</td>
          <td style="padding: 15px 16px;"><span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2" style="border-radius: 30px; font-weight: 500;">${item.retailer_name}</span></td>
          <td class="text-muted" style="padding: 15px 16px;">${formattedDate}</td>
          <td style="padding: 15px 16px;">${item.sales_description}</td>
        `;
        tableBody.appendChild(row);
      });
    }
    
    function createTrendChart(data) {
      const sortedData = [...data].sort((a, b) => {
        return new Date(a.sale_date.replace(/(\d+)\/(\d+)\/(\d+)/, '$3-$2-$1')) - 
               new Date(b.sale_date.replace(/(\d+)\/(\d+)\/(\d+)/, '$3-$2-$1'));
      });
      const ctx = document.getElementById("trendChart").getContext("2d");
      if (trendChart) {
        trendChart.destroy();
      }
      if (data.length === 0) {
        ctx.font = '16px Arial';
        ctx.fillText('No data available', 50, 100);
        return;
      }
      const labels = sortedData.map(item => formatDate(item.sale_date));
      const values = sortedData.map(item => item.amount);
      const cumulativeData = [];
      let sum = 0;
      for (let value of values) {
        sum += value;
        cumulativeData.push(sum);
      }
      trendChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Commission Amount",
              data: values,
              borderColor: "#A945BB", // Brand purple
              backgroundColor: "rgba(169, 69, 187, 0.2)",
              borderWidth: 2,
              fill: true,
              tension: 0.4,
              pointBackgroundColor: "#A945BB",
              pointRadius: 4,
              yAxisID: 'y'
            },
            {
              label: "Cumulative Commission",
              data: cumulativeData,
              borderColor: "#FFD30F", // Brand yellow
              backgroundColor: "rgba(255, 211, 15, 0.1)",
              borderWidth: 2,
              borderDash: [5, 5],
              fill: false,
              tension: 0.4,
              pointBackgroundColor: "#FFD30F",
              pointRadius: 3,
              yAxisID: 'y1'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            tooltip: {
              mode: 'index',
              intersect: false,
              callbacks: {
                label: function (context) {
                  let label = context.dataset.label || '';
                  if (label) {
                    label += ': ';
                  }
                  label += '₹' + numberWithCommas(context.raw);
                  return label;
                }
              }
            },
            legend: {
              position: 'top',
            }
          },
          scales: {
            x: {
              grid: {
                color: 'rgba(169, 69, 187, 0.05)'
              }
            },
            y: {
              beginAtZero: true,
              grid: {
                color: 'rgba(169, 69, 187, 0.05)'
              },
              ticks: {
                callback: function (value) {
                  return '₹' + value;
                }
              }
            },
            y1: {
              beginAtZero: true,
              position: 'right',
              grid: {
                drawOnChartArea: false
              },
              ticks: {
                callback: function (value) {
                  return '₹' + value;
                }
              }
            }
          }
        }
      });
    }
    
    function handleError(message) {
      const tableBody = document.getElementById("commissionTable");
      tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-danger py-5">
        <i class="fas fa-exclamation-circle me-2"></i>${message}</td></tr>`;
      document.getElementById("emptyState").classList.remove('d-none');
      
      // Show toast for data fetch errors
      showErrorToast(message);
    }
    
    function exportToCSV() {
      if (commissionData.length === 0) {
        alert("No data to export");
        return;
      }
      let csvContent = "Amount,Retailer Name,Sale Date,Sales Description\n";
      commissionData.forEach(item => {
        const row = [
          item.amount,
          item.retailer_name.replace(/,/g, ";"),
          item.sale_date,
          item.sales_description.replace(/,/g, ";")
        ].join(",");
        csvContent += row + "\n";
      });
      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const link = document.createElement("a");
      link.setAttribute("href", url);
      link.setAttribute("download", "commission_history.csv");
      link.style.visibility = 'hidden';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
    
    function filterByDate(dateString) {
      if (!dateString) {
        displayCommissions(commissionData);
        createTrendChart(commissionData);
        return;
      }
      const filterDate = new Date(dateString);
      const filterDateStr = filterDate.toISOString().split('T')[0];
      const filteredData = commissionData.filter(item => {
        let itemDate = item.sale_date;
        if (itemDate.includes('/')) {
          const parts = itemDate.split('/');
          itemDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
        return itemDate.startsWith(filterDateStr);
      });
      displayCommissions(filteredData);
      createTrendChart(filteredData);
    }
    
    // Modified requestWithdrawal function - using dialogs for withdrawal outcomes and preventing 0 amount withdrawals
    function requestWithdrawal() {
      if (withdrawableAmount <= 0) {
        showErrorDialog("No funds available for withdrawal. Minimum withdrawal amount is ₹1.");
        return;
      }
      
      axios.post(withdrawalApiUrl,
        { amount: withdrawableAmount }, 
        {
          headers: {
            'Authorization': 'Bearer ' + sessionStorage.getItem('access_token'),
            'Content-Type': 'application/json'
          }
        }
      )
      .then(response => {
        if (response.data.status === "success") {
          const withdrawModal = bootstrap.Modal.getInstance(document.getElementById('withdrawModal'));
          withdrawModal.hide();
          
          // Show success modal for withdrawal
          successModal.show();
          
          // Set up event listener for when success modal is closed
          document.getElementById('successCloseBtn').addEventListener('click', function() {
            location.reload();
          });
          
          // Also reload when success modal is dismissed in other ways
          document.getElementById('successModal').addEventListener('hidden.bs.modal', function() {
            location.reload();
          });
        } else {
          // Use error dialog for withdrawal errors
          showErrorDialog(response.data.message || "Withdrawal request failed");
        }
      })
      .catch(error => {
        console.error("Error making withdrawal:", error);
        // Use error dialog for withdrawal errors
        showErrorDialog(error.response?.data?.message || "Error processing withdrawal request");
      });
    }
    
    document.getElementById("dateFilter").addEventListener("change", function () {
      filterByDate(this.value);
    });
    
    document.getElementById("clearFilter").addEventListener("click", function () {
      document.getElementById("dateFilter").value = "";
      displayCommissions(commissionData);
      createTrendChart(commissionData);
    });
    
    document.getElementById("exportCSV").addEventListener("click", exportToCSV);
    
    document.getElementById("refreshCharts").addEventListener("click", function () {
      createTrendChart(commissionData);
    });
    
    document.getElementById("refreshTable").addEventListener("click", function () {
      fetchCommissions();
      fetchDashboardData();
    });
    
    document.getElementById("confirmWithdraw").addEventListener("click", requestWithdrawal);
    
    // No need for withdraw amount input setup since we removed it
    
    fetchCommissions();
    fetchDashboardData();
  });
</script>
</body>
</html>