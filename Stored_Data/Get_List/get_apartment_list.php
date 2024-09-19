<?php
    require_once "db_conn.php";

    // Fetch category names and store them in the $cat_name array
    $cat_name = [];
    $cat_query = $conn->query("SELECT id, name FROM apartment_categories");
    while ($cat_row = $cat_query->fetch_assoc()) {
        $cat_name[$cat_row['id']] = $cat_row['name'];
    }

    // Fetch apartments from the database
    $rooms_query = $conn->query("SELECT * FROM apartments ORDER BY apart ASC");
    $rooms = [];
    while ($row = $rooms_query->fetch_assoc()) {
        $rooms[] = $row;
    }

    // Generate the HTML table rows
    foreach ($rooms as $index => $row) {
        $i = $index + 1;
        $statusBadge = '';
        if ($row['status'] == 0) {
            $statusBadge = '<span class="badge badge-success">Available</span>';
        } elseif ($row['status'] == 1) {
            $statusBadge = '<span class="badge badge-danger">Unavailable</span>';
        } elseif ($row['status'] == 2) {
            $statusBadge = '<span class="badge badge-maintenance">Maintenance</span>';
        }
    
        // Check if the category ID exists in the $cat_name array
        $categoryName = isset($cat_name[$row['category_id']]) ? $cat_name[$row['category_id']] : '';
        ?>
        <tr>
            <td><?= $i ?></td>
            <td>
                <button class="gallery-button custom-button" data-id="<?= $row["id"] ?>">
                    <span class="glyphicon glyphicon-chevron-right custom-icon"></span>
                </button>
            </td>
            <td><?= $row['apart'] ?></td>
            <td><?= $cat_name[$row['category_id']] ?></td>
            <td><?= $statusBadge ?></td>
            <td>
                <a href="#editModal" class="edit" data-toggle="modal">
                    <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i>
                </a>
                <a href="#" class="delete" data-id="<?= $row["id"] ?>">
                    <i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i>
                </a>
            </td>
        </tr>
    <?php
    }    
?>