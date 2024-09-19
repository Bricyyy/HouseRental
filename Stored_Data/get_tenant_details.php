<?php
    // Database connection
    require_once "db_conn.php";

    // Function to get the tenants for a given apartment ID
    function getTenantsFromApart($conn, $apartmentId) {
        $query = "SELECT * FROM tenants WHERE apartment_id = $apartmentId";
        $result = $conn->query($query);

        // Fetch all tenants as an associative array
        $tenants = $result->fetch_all(MYSQLI_ASSOC);

        // Return the tenants array
        return $tenants;
    }

    // Function to get a single tenant from their ID
    function getTenantFromID($conn, $tenantId) {
        $query = "SELECT t.*, ac.* 
                  FROM tenants t
                  INNER JOIN apartments a ON t.apartment_id = a.id
                  INNER JOIN apartment_categories ac ON a.category_id = ac.id
                  WHERE t.id = $tenantId";
        $result = $conn->query($query);
    
        // Fetch the tenant and apartment category data as an associative array
        $data = $result->fetch_assoc();
    
        // Return the data as an array
        return [$data];
    } 

    // Check if the AJAX request is for getting tenants
    if (isset($_POST['apartmentId'])) {
        // Get the ID
        $apartmentId = $_POST['apartmentId'];

        // Call the function to get the tenants
        $tenants = getTenantsFromApart($conn, $apartmentId);

        // Convert the tenants array to JSON
        $json = json_encode($tenants);

        // Set the response content type to JSON
        header('Content-Type: application/json');

        // Output the JSON response
        echo $json;
    } elseif (isset($_POST['tenantId'])) {
        // Get the ID
        $tenantId = $_POST['tenantId'];

        // Call the function to get the tenant
        $tenant = getTenantFromID($conn, $tenantId);

        // Convert the tenant to JSON
        $json = json_encode($tenant);

        // Set the response content type to JSON
        header('Content-Type: application/json');

        // Output the JSON response
        echo $json;
    }
?>