<?php
    require_once "db_conn.php";

    // Fetch category data from the database
    $categories = [];
    $categories_query = $conn->query("SELECT * FROM apartment_categories ORDER BY id ASC");
    while ($row = $categories_query->fetch_assoc()) {
        $categories[] = $row;
    }

    // Generate the table rows
    foreach ($categories as $i => $category) {
        $formattedAmount = number_format($category['amount'], 2);
        ?>
        <tr>
            <td><?= ($i + 1) ?></td>
            <td><?= $category['name'] ?></td>
            <td>₱<?= $formattedAmount ?></td>
            <td>
                <a href='#editModal' class='edit' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></a>
                <a href='#' class='delete' data-id="<?= $category['id'] ?>"><i class='material-icons' data-toggle='tooltip' title='Delete'>&#xE872;</i></a>
            </td>
        </tr>
        <?php
    }
?>