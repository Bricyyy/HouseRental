<?php
    include "db_conn.php";
    $title = 'Tenants';
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

        <div class="dash-content">
            <div class="overview">
                <div class="table-responsive">
                    <!--Table Starts-->
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h2>Tenants Information</h2>
                                </div>
                                <div class="col-xs-6">
                                    <a href="#addModal" class="btn btn-success" data-toggle="modal" data-target="#addModal"><i class="material-icons">&#xE147;</i> <span>Add New</span></a>
                                </div>
                            </div>
                        </div>
                        <table id="manage-tent" class="table table-borderless table-hover">
                            <thead>
                                <tr>
                                    <th data-column="1">#</th>
                                    <th data-column="2">Name</th>
                                    <th data-column="3">Phone</th>
                                    <th data-column="4">Occupation</th>
                                    <th data-column="5">Apartment Rented</th>
                                    <th data-column="6">Date</th>
                                    <th data-column="7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php include "Stored_Data/Get_List/get_tenant_list.php"; ?>
                            </tbody>
                        </table>
                        <!-- End Table -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Modal HTML -->
    <div id="addModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" id="" method="POST">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="firstname" placeholder="First Name" required="required">
                                </div>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="lastname" placeholder="Last Name" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contact">Phone</label>
                            <input type="number" name="contact" id="contact" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="contact">Occupation</label>
                            <input type="text" name="occupation" id="occupation" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Apartments</label>
                            <select name="apartment_id" id="apartment" class="custom-select" required>
                                <option value="">Loading...</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date_in">Check-in Date</label>
                            <input type="date" name="dt" id="dt" class="form-control"` value="<?php echo isset($meta['dt']) ? date('Y-m-d', strtotime($meta['dt'])) : date('Y-m-d'); ?>" required>
                        </div>

                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                            <input type="submit" class="btn btn-success" name="addTent" value="Add">
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

