<?php
    // Database connection
    require_once 'db_conn.php';

    if (isset($_POST['apartmentId'])) {
        $apartmentId = $_POST['apartmentId'];
        
        $pictures = array();
        $stmt = $conn->prepare("SELECT * FROM apartment_pics WHERE apart_id = ?");
        $stmt->bind_param("i", $apartmentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pictureData = base64_encode($row['picture']);
                $pictures[] = array(
                    "id" => $row['id'],
                    "apart_id" => $row['apart_id'],
                    "filename" => $row['filename'],
                    "picture" => $pictureData
                );
            }
        }

        echo json_encode($pictures);
    }
?>