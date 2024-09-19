<?php
    // Database connection
    require_once "db_conn.php";

    function getApartments($conn) {
        $query = "SELECT * FROM apartments ORDER BY apart ASC";
        $result = $conn->query($query);

        // Fetch all apartments as an associative array
        $apartments = $result->fetch_all(MYSQLI_ASSOC);

        // Return the apartments array
        return $apartments;
    }

    function generateApartmentHTML($apartment) {
        $apartmentId = $apartment['id'];
        $apartmentNo = $apartment['apart'];
        $status = $apartment['status'];
    
        $occupiedButtonOpacity = ($status == 1) ? '1' : '0.5';
        $vacantButtonOpacity = ($status == 0) ? '1' : '0.5';
        $maintenanceButtonOpacity = ($status == 2) ? '1' : '0.5';
    
        $html = '<div class="box" id="apartment-' . $apartmentId . '" onclick="showApartmentDetails(' . $apartmentId . ')">';
        $html .= '<div class="box-content">';
        $html .= '<span class="text">' . $apartmentNo . '</span>';
        $html .= '<div class="btn-group" role="group" aria-label="Occupied, Vacant, and Maintenance Buttons">';
        $html .= '<button type="button" class="btn btn-warning btn-sm';
        if ($status != 1) {
            $html .= ' lighter';
        }
        $html .= '" style="opacity: ' . $occupiedButtonOpacity . '" onclick="updateApartmentStatus(' . $apartmentId . ', 1)" id="unclickable-btn">Occupied</button>';
        $html .= '<button type="button" class="btn btn-success btn-sm';
        if ($status != 0) {
            $html .= ' lighter';
        }
        $html .= '" style="opacity: ' . $vacantButtonOpacity . '" onclick="updateApartmentStatus(' . $apartmentId . ', 0)" id="unclickable-btn">Vacant</button>';
        $html .= '<button type="button" class="btn btn-info btn-sm';
        if ($status != 2) {
            $html .= ' lighter';
        }
        $html .= '" style="opacity: ' . $maintenanceButtonOpacity . '" onclick="updateApartmentStatus(' . $apartmentId . ', 2)" id="unclickable-btn">Maintenance</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
    
        return $html;
    }    

    // Call the function to get the apartments
    $apartments = getApartments($conn);

    // Output the apartments HTML
    foreach ($apartments as $apartment) {
        echo generateApartmentHTML($apartment);
    }
?>