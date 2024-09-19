<?php
    require_once 'db_conn.php';

    if (isset($_GET['id']) && isset($_GET['tableid'])) {
        $id = $_GET['id'];
        $tableId = $_GET['tableid'];
        $tableName = '';

        if ($tableId === 'manage-apart') {
            $tableName = 'apartments';
        } elseif ($tableId === 'manage-cat') {
            $tableName = 'apartment_categories';
        } elseif ($tableId === 'manage-tent') {
            $tableName = 'tenants';

            $selectSql = "SELECT apartment_id FROM tenants WHERE id = ?";
            $stmt = $conn->prepare($selectSql);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            
            $apartmentResult = $stmt->get_result();
            $row = $apartmentResult->fetch_assoc();
            $apartmentId = $row['apartment_id'];
        } elseif ($tableId === 'nested-manage-pay') {
            $tableName = 'payments';
        }

        if ($tableName !== '') {
            $sql = "DELETE FROM $tableName WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0 ) {
                if ($tableId === 'manage-tent') {
                    $updateSql = "UPDATE apartments SET status = 0 WHERE id = ?";
                    $stmt = $conn->prepare($updateSql);
                    $stmt->bind_param("s", $apartmentId);
                    $stmt->execute();
                }

                $response = array('success' => true);
            } else {
                $response = array('error' => false);
            }

            echo json_encode($response);
        }
    } elseif (isset($_POST['imageIds']) && is_array($_POST['imageIds'])) {
        $imageIds = $_POST['imageIds'];

        // Prepare placeholders for the image IDs
        $placeholders = implode(',', array_fill(0, count($imageIds), '?'));
      
        // Prepare a query to delete the images from the database
        $stmt = $conn->prepare("DELETE FROM apartment_pics WHERE id IN ($placeholders)");
      
        // Bind the image IDs as parameters
        $stmt->bind_param(str_repeat('s', count($imageIds)), ...$imageIds);
      
        // Execute the deletion query
        if ($stmt->execute()) {
          // Deletion was successful
          echo json_encode(['success' => true]);
        } else {
          // Deletion failed
          echo json_encode(['success' => false, 'error' => 'Failed to delete images.']);
        }
    } else {
        // No image IDs received
        echo json_encode(['success' => false, 'error' => 'No image IDs provided.']);
    }
?>