<?php 
    session_start();

    include "db_conn.php";
    $title= 'Dashboard';
    require_once 'extras/header.php'; 

    if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
        require_once 'extras/nav.php';
        require_once 'dashboard_content.php';
    } else {
        header("Location: index.php");
        exit();
    }
    
    require_once 'extras/footer.php';
?>

<section class="dashboard">
    <div class="top">
        <div class="search-box">
            <i class="uil uil-search"></i>
            <input type="text" placeholder="Search here...">
        </div>
        <span><?php echo $_SESSION['name']; ?></span>
    </div>

    <div class="dash-content">
        <div class="overview">
            <div class="title">
                <span class="text">Dashboard</span>
            </div>

            <div class="boxes">
                <div class="box box1">
                    <i class="uil uil-estate"></i>
                    <span class="text">Apartments</span>
                    <span class="number"><?php echo $conn->query("SELECT * FROM apartments")->num_rows ?></span>
                </div>
                <div class="box box2">
                    <i class="uil uil-users-alt"></i>
                    <span class="text">Total Tenants</span>
                    <span class="number"><?php echo $conn->query("SELECT * FROM tenants")->num_rows ?></span>
                </div>
                <div class="box box3">
                    <a href="payments.php">
                        <i class="uil uil-transaction"></i>
                        <span class="text">Payments Done</span>
                        <?php
                            $result = $conn->query("SELECT SUM(paid) AS total FROM payments");
                            $row = $result->fetch_assoc();
                            $totalPayments = $row['total'];
                        ?>
                        <span class="number">₱<?php echo number_format($totalPayments, 2); ?></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="activity">
            <div class="title">
                <span class="text">Recent Activity</span>
            </div>
        </div>
    </div>
</section>