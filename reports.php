<?php
    include "db_conn.php";
    require_once 'extras/header.php';
?>

<?php require_once 'extras/nav.php'; ?>

    <section class="dashboard">
        <div class="header-container">
            <div class="row justify-content-start align-items-center mb-4">
                <div class="col-auto">
                    <div class="input-group search-container">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0">
                                <i class="bi bi-search text-dark"></i>
                            </span>
                        </div>
                        <input type="text" id="search-input" class="form-control rounded-pill search-input" placeholder="Search here..." aria-label="Search">
                    </div>
                </div>
            </div>
            <hr class="separator">
        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <span class="text">Reports</span>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card-container overflow-auto scrollbar-hidden">
                        <div class="card card-gradient-1 rounded custom-width">
                            <div class="card-body">
                                <h5 class="card-title">Overall Report</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="card-icon">
                                        <i class="bi bi-bar-chart-line text-primary"></i>
                                    </div>
                                    <div class="card-text">
                                        <span class="number"><?php echo $conn->query("SELECT * FROM payments")->num_rows ?></span>
                                    </div>
                                </div>
                            </div>
                            <a href="report.php" class="card-footer text-primary">
                                View Details <i class="uil uil-angle-double-right"></i>
                            </a>
                        </div>
                        <div class="card card-gradient-2 rounded custom-width">
                            <div class="card-body">
                                <h5 class="card-title">Monthly Report</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="card-icon">
                                        <i class="bi bi-calendar2-check text-danger"></i>
                                    </div>
                                    <div class="card-text">
                                        <span class="number"><?php echo $conn->query("SELECT * FROM payments")->num_rows ?></span>
                                    </div>
                                </div>
                            </div>
                            <a href="month_report.php" class="card-footer text-danger">
                                View Details <i class="uil uil-angle-double-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<?php require_once 'extras/footer.php'; ?>