<?php 
    require_once "controllerUserData.php";

    $name = $_SESSION['name'];
    $password = $_SESSION['password'];
    if($name != false && $password != false){
        $sql = "SELECT * FROM usertable WHERE name = '$name'";
        $run_Sql = mysqli_query($conn, $sql);
        if($run_Sql){
            $fetch_info = mysqli_fetch_assoc($run_Sql);
            $status = $fetch_info['status'];
            $code = $fetch_info['code'];
            if($status == "verified"){
                if($code != 0){
                    header('Location: reset-code.php');
                }
            }else{
                header('Location: user-otp.php');
            }
        }
    }else{
        header('Location: login-user.php');
    }

    // Pass the user's name to JavaScript
    echo "<script>const userName = '$name';</script>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php require_once 'extras/header.php'; ?>
  
  <title>Dashboard</title>
</head>
<body>
  <?php require_once 'extras/nav.php'; ?>

    <section class="dashboard">
        <div class="manage-container">
            <div class="container-separator">
                <!-- <div class="col-lg-10 details-label">
                    <h5>Apartment Details</h5>
                </div>
                <div class="pictures-container">
                    <div class="apartment-picture">
                    </div>
                </div>
                <div class="details-container"></div> -->
            </div>

            <div class="side-container">
                <div class="calendar">
                    <div class="calendar-header">
                        <h4 class="calendar-header-title fw-bold"></h4>
                        <div class="calendar-header-buttons">
                            <div class="calendar-header-left">
                                <i class="fas fa-chevron-left"></i>
                            </div>
                            <div class="calendar-header-right">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                    <div class="calendar-body">
                        <div class="calendar-weekdays">
                            <!-- Weekdays will be dynamically generated here -->
                        </div>
                        <div class="calendar-dates">
                            <!-- Dates will be dynamically generated here -->
                        </div>
                        <div class="calendar-container"></div>
                    </div>
                </div>
                <div class="latest-transactions">
                    <div class="transaction-header">
                        <h5 class="latest-lbl">Recent Payments</h5>
                    </div>

                    <div class="transaction-list">
                        <!-- Payment items will be dynamically generated here -->
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-start align-items-center mb-4">
                <div class="col-auto">
                    <div class="input-group search-container">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0">
                                <i class="bi bi-search text-dark"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control rounded-pill search-input" placeholder="Search here..." aria-label="Search">
                    </div>
                </div>
            </div>
            <hr class="separator">

            <div class="row align-items-center" class="main-card">
                <div class="col-lg-4 mb-4">
                    <h3 class="fw-bold" id="greeting"></h3>
                    <p class="fs-5" style="color: #1e1d30;">Here you can track the updates on different<br>sections to manage them easily.</p>
                </div>
                <div class="col-lg-6 mb-4 wrap-card">
                    <div class="card-container overflow-auto scrollbar-hidden">
                        <div class="card card-gradient-1 rounded custom-width">
                            <div class="card-body">
                                <h5 class="card-title">Apartments</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="card-icon">
                                        <i class="uil uil-estate text-primary"></i>
                                    </div>
                                    <div class="card-text">
                                        <span class="number"><?php echo $conn->query("SELECT * FROM apartments")->num_rows ?></span>
                                    </div>
                                </div>
                            </div>
                            <a href="apartments.php" class="card-footer text-primary">
                                View Details <i class="uil uil-angle-double-right"></i>
                            </a>
                        </div>
                        <div class="card card-gradient-2 rounded custom-width">
                            <div class="card-body">
                                <h5 class="card-title">Total Tenants</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="card-icon">
                                        <i class="uil uil-users-alt text-danger"></i>
                                    </div>
                                    <div class="card-text">
                                        <span class="number"><?php echo $conn->query("SELECT * FROM tenants")->num_rows ?></span>
                                    </div>
                                </div>
                            </div>
                            <a href="tenants.php" class="card-footer text-danger">
                                View Details <i class="uil uil-angle-double-right"></i>
                            </a>
                        </div>
                        <div class="card card-gradient-3 rounded custom-width">
                            <div class="card-body">
                                <h5 class="card-title">Payments Done</h5>
                                <?php
                                    $result = $conn->query("SELECT SUM(paid) AS total FROM payments");
                                    $row = $result->fetch_assoc();
                                    $totalPayments = $row['total'];
                                ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="card-icon">
                                        <i class="uil uil-transaction text-success"></i>
                                    </div>
                                    <div class="card-text">
                                        <span class="number">₱<?php echo number_format($totalPayments, 2); ?></span>
                                    </div>
                                </div>
                            </div>
                            <a href="payments.php" class="card-footer text-success">
                                View Details <i class="uil uil-angle-double-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row overview-container">
                <div class="col-lg-12">
                    <h5>Apartments Overview</h5>
                </div>
                <div class="col-lg-12">
                    <div class="apartment-list">
                        <!-- Apartments will be dynamically generated here -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once 'extras/footer.php'; ?>

    <script src="javascript/dashboard.js"></script>
</body>
</html>