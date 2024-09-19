<?php
    include "db_conn.php";
    $title = 'Apartment Payments';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include 'extras/header.php'; ?>
    
    <title>HRMS - <?php echo $title; ?> </title>
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
                                    <h2>Payment List</h2>
                                </div>
                                <div class="col-xs-6">
                                    <a href="#addModal" id="submitCat" class="btn btn-success" data-toggle="modal" data-target="#addModal"><i class="material-icons">&#xE147;</i> <span>Add New</span></a>
                                </div>
                            </div>
                        </div>
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
                        <!-- End Table -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ADD Modal HTML -->
    <div id="addModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" id="" method="POST">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tenant">Tenants</label>
                            <select name="tenant_id" id="tenant" class="custom-select" required>
                                <option value="">Loading...</option>
                            </select>
                        </div>
                        <div id="tenant-details"></div>
                        <div class="form-group">
                            <label for="invoice">Invoice No.</label>
                            <input type="text" class="form-control" name="invoice" id="invoice" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" name="amount" required>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel" onclick="resetSelection()">
                            <input type="submit" class="btn btn-success" name="addPay" value="Add">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'extras/footer.php'; ?>

    <script src="javascript/edit_data.js"></script>
</body>
</html>

<?php
    require_once "Stored_Data/insert_data.php";
?>