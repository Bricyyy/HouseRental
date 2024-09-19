<?php
include "db_conn.php";
$title = 'overall_report';

$sql = "SELECT * FROM payments GROUP BY id";
$result = $conn->query($sql) or die($conn->connect_error);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include 'extras/header.php'; ?>

    <title>HRMS -
        <?php echo $title; ?>
    </title>
</head>

<body>
    <?php include 'extras/nav.php'; ?>

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

        <?php include "Stored_Data/Get_List/get_payment_list.php"; ?>
        <!-- Display the main table -->
        <div class="dash-content">
            <div class="overview">
                <div class="table-responsive">
                    <!--Table Starts-->
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h2> Overall Reports</h2>
                                </div>
                                <div class="col-xs-6">
                                    <a href="data-print.php" id="submitCat" class="btn btn-danger" data-toggle="modal"
                                        data-target="#addModal"><i class="material-icons"></i> <span>Print</span></a>
                                </div>
                            </div>
                        </div>
                        <table id="overall_report" class="table table-borderless table-hover">
                            <thead>
                                <tr>
                                    <th data-column="1">#</th>
                                    <th data-column="2">Tenant</th>
                                    <th data-column="3">Apartment</th>
                                    <th data-column="4">Monthly Rate</th>
                                    <th data-column="5">Payable Months</th>
                                    <th data-column="6">Total Paid</th>
                                    <th data-column="7">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rowNumber = 1; // Initialize the row number
                                
                                foreach ($tenants as $tenant) {
                                    $price = number_format($tenant['amount'], 2);
                                    $paid = number_format($tenant['paid'], 2);
                                    $balance = number_format($tenant['balance'], 2);
                                    ?>

                                    <tr data-bs-toggle="collapse" data-bs-target="#nestedRow<?= $rowNumber ?>"
                                        class="accordion-toggle">
                                        <td>
                                            <?php echo $rowNumber ?>
                                        </td>
                                        <td>
                                            <?= $tenant['tenant'] ?>
                                        </td>
                                        <td>
                                            <?= $tenant['apartment'] ?>
                                        </td>
                                        <td>₱
                                            <?= $price ?>
                                        </td>
                                        <td>
                                            <?= $tenant['monthsAhead'] ?>
                                        </td>
                                        <td>₱
                                            <?= $paid ?>
                                        </td>
                                        <td>₱
                                            <?= $balance ?>
                                        </td>
                                    </tr>
                            
    
                    <?php
                    $rowNumber++; // Increment the row number
                                }
                                ?>
                </tbody>
                </table>
                <!-- End Table -->
                <?php
                $result = mysqli_query($conn, 'SELECT SUM(paid) AS value_sum FROM payments');
                $row = mysqli_fetch_assoc($result);
                $sum = $row['value_sum']; ?>
                <?php echo "Total amount paid: $sum"; ?><br>
            </div>
        </div>
        </div>
        </div>
        <div class="container">
            <button type="" class="btn btn-info noprint" style="width: 85%"
                onclick="window.location.replace('reports.php');">Cancel</button>
        </div>
    </section>

</body>

</html>