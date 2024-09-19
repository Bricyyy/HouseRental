<?php
    // Database connection
    require_once 'db_conn.php';

    $apartment = array();
    $result = $conn->query("SELECT * FROM apartments ORDER BY apart ASC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $apartment[] = array(
                "id" => $row['id'],
                "category_id" => $row['category_id'],
                "apart" => $row['apart'],
                "status" => $row['status']
            );
        }
    }

    echo json_encode($apartment);
?>