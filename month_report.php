<?php
include "db_conn.php";
$title= 'month_report';

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
                                    <h2>Monthly Reports</h2>
                                </div>
                                <div class="col-xs-6">
                                    <a href="month-print.php" id="submitCat" class="btn btn-danger" data-toggle="modal"
                                        data-target="#addModal"><i class="material-icons"></i> <span>Print</span></a>
                                </div>
                            </div>
                        </div>
                        <table id="month_report" class="table table-borderless table-hover">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Amount Paid</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT * FROM payments");
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $row['id']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['paid']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['date_created']; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                        </table>

                        <?php
                        $result = mysqli_query($conn, 'SELECT SUM(paid) AS value_sum FROM payments');
                        $row = mysqli_fetch_assoc($result);
                        $sum = $row['value_sum']; ?>
                        <?php echo "Total amount paid: $sum"; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <button type="" class="btn btn-info noprint" style="width: 85%"
                onclick="window.location.replace('reports.php');">Cancel</button>
        </div>
    </section>
    <?php $conn->close(); ?>
</body>

</html>