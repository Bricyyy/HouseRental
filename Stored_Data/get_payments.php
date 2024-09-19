<?php
    // Database connection
    require_once 'db_conn.php';

    $tenants = array();
    $result = $conn->query("SELECT * FROM payments");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tenants[] = array(
                "id" => $row['id'],
                "tenant_id" => $row['tenant_id'],
                "amount" => $row['paid'],
                "invoice" => $row['invoice'],
                "date_created" => $row['date_created']
            );
        }
    }

    echo json_encode($tenants);
?>