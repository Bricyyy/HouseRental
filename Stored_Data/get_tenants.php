<?php
    // Database connection
    require_once 'db_conn.php';

    $tenants = array();
    $result = $conn->query("SELECT * FROM tenants ORDER BY lastname ASC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tenants[] = array(
                "id" => $row['id'],
                "firstname" => $row['firstname'],
                "lastname" => $row['lastname'],
                "contact" => $row['contact'],
                "occupation" => $row['occupation'],
                "apartment_id" => $row['apartment_id'],
                "status" => $row['status'],
                "dt" => $row['dt']
            );
        }
    }

    echo json_encode($tenants);
?>