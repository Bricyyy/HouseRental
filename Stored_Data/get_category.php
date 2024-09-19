<?php
    // Database connection
    require_once 'db_conn.php';

    $categories = array();
    $result = $conn->query("SELECT * FROM apartment_categories ORDER BY name ASC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "amount" => $row['amount']
            );
        }
    }

    echo json_encode($categories);
?>