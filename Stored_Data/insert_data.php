<?php
    require_once 'db_conn.php';

    class DataAdder
    {
        private $db;

        public function __construct($db)
        {
            $this->db = $db;
        }

        public function addApartment($category_id, $apart, $status)
        {
            $sql = "INSERT INTO apartments (id, category_id, apart, status) VALUES (NULL, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iss", $category_id, $apart, $status);
            $result = $stmt->execute();

            return $result;
        }

        public function addCategory($name, $amount)
        {
            $sql = "INSERT INTO apartment_categories (id, name, amount) VALUES (NULL, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("si", $name, $amount);
            $result = $stmt->execute();

            return $result;
        }

        public function addTenant($firstname, $lastname, $contact, $occupation, $apartment_id)
        {
            $sql = "INSERT INTO tenants (id, firstname, lastname, contact, occupation, apartment_id, status, dt)
                    VALUES (NULL, ?, ?, ?, ?, ?, 1, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssssi", $firstname, $lastname, $contact, $occupation, $apartment_id);
            $result = $stmt->execute();
    
            if ($result) {
                $updateSql = "UPDATE apartments SET status = 1 WHERE id = ?";
                $stmt = $this->db->prepare($updateSql);
                $stmt->bind_param("i", $apartment_id);
                $stmt->execute();
            }
    
            return $result;
        }

        public function addPay($tenant_id, $amount, $invoice)
        {
            $sql = "INSERT INTO payments (id, tenant_id, paid, invoice, date_created) VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iis", $tenant_id, $amount, $invoice);
            $result = $stmt->execute();

            return $result;
        }

        public function addPicture($apartId, $fileName, $picture)
        {
            $imageData = base64_decode($picture);
    
            $sql = "INSERT INTO apartment_pics (id, apart_id, filename, picture) VALUES (NULL, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iss", $apartId, $fileName, $imageData);
            $result = $stmt->execute();
    
            return $result;
        }
    }

    if (isset($_POST['addApart'])) {
        $category_id = $_POST['category_id'];
        $apart = $_POST['apart'];
        $status = $_POST['status'];

        // Assuming $conn is the database connection object
        $DataAdder = new DataAdder($conn);
        $result = $DataAdder->addApartment($category_id, $apart, $status);

        if ($result) {
            $successMessage = "Apartment added successfully";
            $redirectUrl = 'apartments.php';
        } else {
            $errorMessage = "Failed to add Apartment";
        }
    }

    if (isset($_POST['addCat'])) {
        $name = $_POST['catName'];
        $amount = $_POST['catAmount'];

        $DataAdder = new DataAdder($conn);
        $result = $DataAdder->addCategory($name, $amount);

        if ($result) {
            $successMessage = "Category added successfully";
            $redirectUrl = 'categories.php';
        } else {
            $errorMessage = "Failed to add Category";
        }
    }

    if (isset($_POST['addTent'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $contact = $_POST['contact'];
        $occupation = $_POST['occupation'];
        $apartment_id = $_POST['apartment_id'];
    
        $DataAdder = new DataAdder($conn);
        $result = $DataAdder->addTenant($firstname, $lastname, $contact, $occupation, $apartment_id);
    
        if ($result) {
            $successMessage = "Tenant added successfully";
            $redirectUrl = 'tenants.php';
        } else {
            $errorMessage = "Failed to add Tenant";
        }
    }

    if (isset($_POST['addPay'])) {
        $tenant_id = $_POST['tenant_id'];
        $amount = $_POST['amount'];
        $invoice = $_POST['invoice'];
    
        $DataAdder = new DataAdder($conn);
        $result = $DataAdder->addPay($tenant_id, $amount, $invoice);
    
        if ($result) {
            $successMessage = "Payment added successfully";
            $redirectUrl = 'payments.php';
        } else {
            $errorMessage = "Failed to add Payment";
        }
    }

    if (isset($_POST['apartId'], $_POST['fileName'], $_POST['picture'])) {
        $apartId = $_POST['apartId'];
        $fileName = $_POST['fileName'];
        $picture = $_POST['picture'];
    
        $DataAdder = new DataAdder($conn);
        $result = $DataAdder->addPicture($apartId, $fileName, $picture);
    
        if ($result) {
            $successMessage = "Image added successfully";
            $redirectUrl = 'apartments.php';
        } else {
            $errorMessage = "Failed to add Image";
        }
    }
?>

<?php if (isset($successMessage)) : ?>
    <script>
        swal({
            title: '<?= $successMessage ?>',
            icon: 'success',
        }).then(() => {
            window.open('<?= $redirectUrl ?>','_self');
        });
    </script>
<?php endif; ?>

<?php if (isset($errorMessage)) : ?>
    <script>
        swal({
            title: '<?= $errorMessage ?>',
            icon: 'error',
        });
    </script>
<?php endif; ?>