<?php
include 'db_conn.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Print</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
</head>

<body onload="print()">
    <?php include "Stored_Data/Get_List/get_payment_list.php"; ?>
    <div class="container">
        <center>
            <h3 style="margin-top: 30px;">Report</h3>
            <hr>
        </center>
        <table id="manage-pay" class="table table-borderless table-hover">
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
                            <tr data-bs-toggle="collapse" data-bs-target="#nestedRow<?= $rowNumber ?>" class="accordion-toggle">
                                <td><?= $rowNumber ?></td>
                                <td><?= $tenant['tenant'] ?></td>
                                <td><?= $tenant['apartment'] ?></td>
                                <td>₱<?= $price ?></td>
                                <td><?= $tenant['monthsAhead'] ?></td>
                                <td>₱<?= $paid ?></td>
                                <td>₱<?= $balance ?></td>
                            </tr>
                            <tr>
                                <td colspan="7" class="p-0">
                                    <div class="accordion-collapse collapse" id="nestedRow<?= $rowNumber ?>">
                                        <div class="nested-table table-container">
                                            <table class="table table-borderless table-hover" id="nested-manage-pay">
                                                <thead>
                                                    <tr>
                                                        <th data-column="1">#</th>
                                                        <th data-column="2">Date</th>
                                                        <th data-column="3">Invoice No.</th>
                                                        <th data-column="4">Amount</th>
                                                        <th data-column="5">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $nestedRowNumber = 1;

                                                        foreach ($tenant['payments'] as $payment) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <div class="tree-line"></div>
                                                            <div class="horizontal-line"></div>
                                                            <?= $nestedRowNumber ?>
                                                        </td>
                                                        <td><?= $payment['date'] ?></td>
                                                        <td><?= $payment['invoice'] ?></td>
                                                        <td>₱<?= number_format($payment['paid'], 2) ?></td>
                                                        <td>
                                                            <a href='#editModal' class='edit' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></a>
                                                            <a href='#' class='delete' data-id="<?= $payment['id'] ?>"><i class='material-icons' data-toggle='tooltip' title='Delete'>&#xE872;</i></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                        $nestedRowNumber++;
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <div class="tree-line"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                                $rowNumber++; // Increment the row number
                                }
                            ?>
                        </tbody>
                    </table>
                    <?php
                        $result = mysqli_query($conn, 'SELECT SUM(paid) AS value_sum FROM payments');
                        $row = mysqli_fetch_assoc($result);
                        $sum = $row['value_sum']; ?>
                        <?php echo "Total amount paid: $sum"; ?><br><br>
    </div>
    <div class="container">
        <button type="" class="btn btn-info noprint" style="width: 100%"
            onclick="window.location.replace('reports.php');">Cancel</button>
    </div>

</body>

</html>