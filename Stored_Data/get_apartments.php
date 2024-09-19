<?php
    // Database connection
    require_once 'db_conn.php';

    $apartment = array();
    $result = $conn->query("SELECT * FROM apartments WHERE status = 0 AND id NOT IN (SELECT apartment_id FROM tenants) ORDER BY apart ASC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $apartment[] = array(
                "id" => $row['id'],
                "apart" => $row['apart']
            );
        }
    }

    echo json_encode($apartment);
?>