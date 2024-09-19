<?php
	include "db_conn.php";
	$title= 'Apartments';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php require_once 'extras/header.php'; ?>

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

        <!-- <div class="top">
            <div class="search-box">
                <i class="uil uil-search"></i>
                <input type="text" id="search-input" placeholder="Search here...">
            </div>
        </div> -->

		<!--Apartment Status	
        <div class="dash-content">
				<div class="overview">
					<div class="title">
						<span class="text">Apartment</span>
					</div>
				</div>
				<div class="scroll-container">
					<div id="apartmentContainer" class="scrolling-container">
						<?php include 'Stored_Data/generateApartment.php'; ?>
					</div>
					<div class="scroll-buttons">
						<button id="scrollLeftButton" class="scroll-button">
							<i class="fas fa-chevron-left"></i>
						</button>
						<button id="scrollRightButton" class="scroll-button">
							<i class="fas fa-chevron-right"></i>
						</button>
					</div>
				</div>
			</div>
			<div id="tenant-details-container"></div>
		-->	

		<!--Table Starts-->
        <div class="dash-content">
            <div class="overview">
				<div class="table-responsive">		
					<div class="table-wrapper">
						<div class="table-title">
							<div class="row">
								<div class="col-xs-6">
									<h2>Apartment List</h2>
								</div>
								<div class="col-xs-6">
									<a href="#addModal" class="btn btn-success" data-toggle="modal" data-target="#addModal"><i class="material-icons">&#xE147;</i> <span>Add New</span></a>						
								</div>
							</div>
						</div>
                        <div class="table-container">
                            <table id="manage-apart" class="table table-borderless table-hover">
                                <thead>
                                    <tr>
                                        <th data-column="1">#</th>
                                        <th data-column="2">Pictures</th>
                                        <th data-column="3">Apartment No.</th>
                                        <th data-column="4">Category</th>
                                        <th data-column="5">Status</th>
                                        <th data-column="6">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php include "Stored_Data/Get_List/get_apartment_list.php"; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="images-container overflow-auto"></div>
					</div>
				</div>
			</div>        
    	</div>
		<!-- End Table -->	
	</section>

    <!-- Add New Modal -->
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
                            <label class="">Apartment No.</label>
                            <input type="text" class="form-control" name="apart" required>
                        </div>
                        <!-- Select from apartment categories name in database -->
                        <div class="form-group">
                            <label class="control-label">Category</label>
                            <select name="category_id" id="category" class="custom-select" required>
                                <option value="">Loading...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Availability</label>
                            <select class="custom-select" name="status" required>
                                <option value="0">Available</option>
                                <option value="1">Unavailable</option>
                                <option value="2">Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-success"  name="addApart"  value="Add">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <?php require_once 'extras/footer.php'; ?>

    <script src="javascript/edit_data.js"></script>
</body>
</html>

<?php 
    require_once "Stored_Data/insert_data.php";
?>