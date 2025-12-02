<?php
/**
 * Advanced Reports & Analytics Page  
 * Additional Tools Module
 * Generate comprehensive reports across all platform modules
 */

// Start session and include necessary files
session_start();
require_once 'includes/db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$page_title = "Advanced Reports";

// ==================== VIEW LAYER ====================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Smart Laptop Advisor Admin</title>
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="source/assets/css/bootstrap.css">
    <link rel="stylesheet" href="source/assets/vendors/iconly/bold.css">
    <link rel="stylesheet" href="source/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="source/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="source/assets/vendors/apexcharts/apexcharts.css">
    <link rel="stylesheet" href="source/assets/css/app.css">
    <link rel="shortcut icon" href="source/assets/images/favicon.svg" type="image/x-icon">
</head>

<body>
    <div id="app">
        <?php include 'includes/admin_header.php'; ?>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Comprehensive Reports & Analytics</h3>
                            <p class="text-subtitle text-muted">Generate detailed reports and analytics for all platform modules</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Reports</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                
                <!-- Report Filters -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Report Filters & Export</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="reportType">Report Type</label>
                                            <select class="form-select" id="reportType">
                                                <option value="all">All Reports</option>
                                                <option value="sales">Sales & Revenue</option>
                                                <option value="products">Product Performance</option>
                                                <option value="customers">Customer Analytics</option>
                                                <option value="ai">AI Recommendations</option>
                                                <option value="chatbot">Chatbot Performance</option>
                                                <option value="inventory">Inventory Reports</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="dateFrom">From Date</label>
                                            <input type="date" class="form-control" id="dateFrom" value="<?= date('Y-01-01') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="dateTo">To Date</label>
                                            <input type="date" class="form-control" id="dateTo" value="<?= date('Y-12-31') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div class="btn-group w-100">
                                                <button type="button" class="btn btn-primary" onclick="generateReport()">
                                                    <i class="bi bi-search"></i> Generate
                                                </button>
                                                <button type="button" class="btn btn-success" onclick="exportReport()">
                                                    <i class="bi bi-download"></i> Export CSV
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon purple">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Total Orders</h6>
                                        <h6 class="font-extrabold mb-0" id="summary-orders">-</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Total Revenue</h6>
                                        <h6 class="font-extrabold mb-0" id="summary-revenue">-</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="iconly-boldAdd-User"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">New Users</h6>
                                        <h6 class="font-extrabold mb-0" id="summary-users">-</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon red">
                                            <i class="iconly-boldBookmark"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Report Exports</h6>
                                        <h6 class="font-extrabold mb-0">567</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts and detailed views would go here -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 id="chart-title">Sales & Revenue Analytics</h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-sales-revenue"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'includes/admin_footer.php'; ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="source/assets/js/bootstrap.js"></script>
    <script src="source/assets/js/app.js"></script>
    <script src="source/assets/vendors/apexcharts/apexcharts.js"></script>

    <script>
        // Initialize Charts
        var salesRevenueOptions = {
            series: [],
            chart: {
                type: 'line',
                height: 350,
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth' },
            xaxis: {
                categories: [],
                type: 'datetime'
            },
            tooltip: {
                x: { format: 'dd MMM yyyy' }
            },
            noData: {
                text: 'Loading...'
            }
        };
        var salesRevenueChart = new ApexCharts(document.querySelector("#chart-sales-revenue"), salesRevenueOptions);
        salesRevenueChart.render();

        function generateReport() {
            const reportType = document.getElementById('reportType').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            
            // Show loading state
            const btn = document.querySelector('button[onclick="generateReport()"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...';
            btn.disabled = true;

            // Update Chart Title
            const reportTypeText = document.getElementById('reportType').options[document.getElementById('reportType').selectedIndex].text;
            document.getElementById('chart-title').textContent = reportTypeText + ' Analytics';

            fetch(`ajax/report_handler.php?action=generate&reportType=${reportType}&dateFrom=${dateFrom}&dateTo=${dateTo}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateDashboard(data);
                    } else {
                        alert('Error generating report: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while generating the report.');
                })
                .finally(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        }

        function updateDashboard(data) {
            // Update Summary Cards
            const cards = document.querySelectorAll('.card-body .row .col-md-8');
            if (cards.length >= 3) {
                // Card 1
                cards[0].querySelector('h6.text-muted').textContent = data.summary.card1.title;
                cards[0].querySelector('h6.font-extrabold').textContent = data.summary.card1.value;
                
                // Card 2
                cards[1].querySelector('h6.text-muted').textContent = data.summary.card2.title;
                cards[1].querySelector('h6.font-extrabold').textContent = data.summary.card2.value;
                
                // Card 3
                cards[2].querySelector('h6.text-muted').textContent = data.summary.card3.title;
                cards[2].querySelector('h6.font-extrabold').textContent = data.summary.card3.value;
            }

            // Determine chart type and axis type
            let xaxisType = 'category';
            if (data.chart.type === 'line' || data.chart.type === 'area') {
                xaxisType = 'datetime';
            }

            // Update Chart
            salesRevenueOptions = {
                series: data.chart.series,
                chart: {
                    type: data.chart.type,
                    height: 350
                },
                xaxis: {
                    categories: data.chart.categories,
                    type: xaxisType
                },
                labels: data.chart.type === 'donut' ? data.chart.categories : undefined
            };
            
            // Destroy and re-create to ensure clean state switching between types
            if (salesRevenueChart) {
                salesRevenueChart.destroy();
            }
            salesRevenueChart = new ApexCharts(document.querySelector("#chart-sales-revenue"), salesRevenueOptions);
            salesRevenueChart.render();
        }

        function exportReport() {
            const reportType = document.getElementById('reportType').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            
            window.location.href = `ajax/report_handler.php?action=export&reportType=${reportType}&dateFrom=${dateFrom}&dateTo=${dateTo}`;
        }

        // Load initial data
        document.addEventListener('DOMContentLoaded', function() {
            generateReport();
        });
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
