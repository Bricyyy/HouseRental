<?php
    require_once 'db_conn.php';

    class DataUpdater
    {
        private $db;

        public function __construct($db)
        {
            $this->db = $db;
        }

        public function updateApartment($id, $categoryID, $apart, $status)
        {
            $sql = "UPDATE apartments SET category_id = ?, apart = ?, status = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("isii", $categoryID, $apart, $status, $id);
            $result = $stmt->execute();

            return $result;
        }

        public function updateApartmentStatus($id, $status)
        {
            $sql = "UPDATE apartments SET status = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $status, $id);
            $result = $stmt->execute();

            return $result;
        }

        public function updateCategory($id, $name, $amount)
        {
            $sql = "UPDATE apartment_categories SET name = ?, amount = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sii", $name, $amount, $id);
            $result = $stmt->execute();

            return $result;
        }

        public function updateTenant($id, $fistname, $lastname, $contact, $occupation, $apartmentId, $date)
        {
            $sql = "UPDATE tenants SET firstname = ?, lastname = ?, contact = ?, occupation = ?, apartment_id = ?, dt = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssisisi", $fistname, $lastname, $contact, $occupation, $apartmentId, $date, $id);
            $result = $stmt->execute();

            return $result;
        }

        public function updatePayment($id, $paid)
        {
            $sql = "UPDATE payments SET paid = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $paid, $id);
            $result = $stmt->execute();

            return $result;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['primaryKey'], $_POST['Category'], $_POST['apartmentNo'], $_POST['Status'])) {
            $id = $_POST['primaryKey'];
            $categoryID = $_POST['Category'];
            $apart = $_POST['apartmentNo'];
            $status = $_POST['Status'];
    
            $DataUpdater = new DataUpdater($conn);
            $result = $DataUpdater->updateApartment($id, $categoryID, $apart, $status);
    
            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Apartment updated successfully',
                    'redirectUrl' => 'apartments.php'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to update Apartment'
                );
            }

            echo json_encode($response);
            exit;
        } elseif (isset($_POST['primaryKey'], $_POST['apartmentType'], $_POST['Price'])) {
            $id = $_POST['primaryKey'];
            $name = $_POST['apartmentType'];
            $price = $_POST['Price'];
    
            $DataUpdater = new DataUpdater($conn);
            $result = $DataUpdater->updateCategory($id, $name, $price);
    
            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Category updated successfully',
                    'redirectUrl' => 'categories.php'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to update Category'
                );
            }
    
            echo json_encode($response);
            exit;
        } elseif (isset($_POST['primaryKey'], $_POST['firstName'], $_POST['lastName'])) {
            $id = $_POST['primaryKey'];
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $contact = $_POST['Phone'];
            $occupation = $_POST['Occupation'];
            $apartmentId = $_POST['apartmentRented'];
            $date = $_POST['Date'];
            $prevApartId = $_POST['origApartment'];
    
            $DataUpdater = new DataUpdater($conn);
            $result = $DataUpdater->updateTenant($id, $firstName, $lastName, $contact, $occupation, $apartmentId, $date);

            if ($result) {
                if ($apartmentId == $prevApartId) {
                    $DataUpdater->updateApartmentStatus($apartmentId, 1);
                } else {
                    $DataUpdater->updateApartmentStatus($apartmentId, 1);
                    $DataUpdater->updateApartmentStatus($prevApartId, 0);
                }

                $response = array(
                    'status' => 'success',
                    'message' => 'Tenant updated successfully',
                    'redirectUrl' => 'tenants.php'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to update Tenant'
                );
            }

            echo json_encode($response);
            exit;
        } elseif (isset($_POST['primaryKey'], $_POST['Invoice'])) {
            $id = $_POST['primaryKey']; 
            $amount = $_POST['Amount'];
    
            $DataUpdater = new DataUpdater($conn);
            $result = $DataUpdater->updatePayment($id, $amount);
    
            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Payment updated successfully',
                    'redirectUrl' => 'payments.php'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Failed to update Payment'
                );
            }
    
            echo json_encode($response);
            exit;
        }
    }
?>