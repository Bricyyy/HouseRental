<?php
    // Database connection
    require_once "db_conn.php";

    // Check if the form data has been submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Retrieve the apartment ID and status from the form data
        $apartmentId = $_POST['apartmentId'];
        $status = $_POST['status'];

        // Update the apartment status in the database
        $sql = "UPDATE apartments SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $status, $apartmentId);

        if ($stmt->execute()) {
            echo "Apartment status updated successfully";
        } else {
            echo "Apartment status update failed";
        }
    }
?>