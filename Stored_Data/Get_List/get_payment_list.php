<?php
    require_once "db_conn.php";

    // Fetch payments data from the database
    $tenants = []; // Array to store unique tenants
    $query = "SELECT p.*, t.firstname, t.lastname, t.apartment_id, a.apart, ac.amount, t.dt
              FROM payments p
              INNER JOIN tenants t ON p.tenant_id = t.id
              INNER JOIN apartments a ON t.apartment_id = a.id
              INNER JOIN apartment_categories ac ON a.category_id = ac.id
              ORDER BY p.date_created DESC";
    $payments_query = $conn->query($query);
    while ($row = $payments_query->fetch_assoc()) {
        $tenantId = $row['tenant_id'];
        if (!isset($tenants[$tenantId])) {
            $tenants[$tenantId] = [
                'tenant' => $row['lastname'] . ', ' . $row['firstname'],
                'apartment' => $row['apart'],
                'amount' => $row['amount'],
                'paid' => 0,
                'monthsAhead' => 0,
                'balance' => 0,
                'payments' => []
            ];
        }

        $tenants[$tenantId]['paid'] += $row['paid'];
        $tenants[$tenantId]['monthsAhead'] = calculateMonthsAhead($row['dt']);
        $tenants[$tenantId]['balance'] = ($tenants[$tenantId]['monthsAhead'] * $tenants[$tenantId]['amount']) - $tenants[$tenantId]['paid'];

        // Add payment information to the tenant's payments array
        $tenants[$tenantId]['payments'][] = [
            'date' => formatDate($row['date_created']),
            'invoice' => $row['invoice'],
            'paid' => $row['paid'],
            'id' => $row['id']
        ];
    }

    // Function to calculate the months ahead
    function calculateMonthsAhead($date)
    {
        $today = new DateTime();
        $tenantDate = new DateTime($date);

        $interval = $today->diff($tenantDate);
        $monthsAhead = $interval->y * 12 + $interval->m;

        return $monthsAhead;
    }

    // Function to format the date
    function formatDate($date)
    {
        $dateTime = new DateTime($date);
        $formattedDate = $dateTime->format('F d, Y - g:iA');
        return $formattedDate;
    }
?>