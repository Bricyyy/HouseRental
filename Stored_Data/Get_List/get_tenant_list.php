<?php
    require_once "db_conn.php";

    $tenantData = [];

    $query = "SELECT tenants.id, tenants.firstname, tenants.lastname, tenants.contact, tenants.occupation, tenants.dt, apartment_categories.name AS room_rented, apartments.apart
              FROM tenants
              INNER JOIN apartments ON tenants.apartment_id = apartments.id
              INNER JOIN apartment_categories ON apartments.category_id = apartment_categories.id
              ORDER BY tenants.id ASC";

    $tenantResult = $conn->query($query);

    while ($row = $tenantResult->fetch_assoc()) {
        $tenantData[] = $row;
    }

    foreach ($tenantData as $i => $tenant) {
        $fullName = $tenant["firstname"] . " " . $tenant["lastname"];
        $formattedDate = date("F d, Y", strtotime($tenant["dt"]));

        echo "<tr>";
        echo "<td>" . ($i + 1) . "</td>";
        echo "<td>" . $fullName . "</td>";
        echo "<td>" . $tenant["contact"] . "</td>";
        echo "<td>" . $tenant["occupation"] . "</td>";
        echo "<td>" . $tenant["room_rented"] . " | " . $tenant["apart"] . "</td>";
        echo "<td>" . $formattedDate . "</td>";
        echo '<td>
                <!-- Link to delete the tenant -->
                <a href="#editModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                <a href="#" class="delete" data-id="' . $tenant["id"] . '"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
              </td>';
        echo "</tr>";
    }
?>