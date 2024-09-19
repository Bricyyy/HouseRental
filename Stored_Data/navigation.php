<?php
    // index.php

    // Get the value of the 'page' query parameter
    $page = isset($_GET['page']) ? $_GET['page'] : '';

    // Define the allowed pages and their corresponding file names
    $pages = [
        'dashboard' => 'dashboard.php',
        'categories' => 'categories.php',
        'apartments' => 'apartments.php',
        'tenants' => 'tenants.php',
        'payments' => 'payments.php',
        'reports' => 'reports.php'
    ];

    // Check if the requested page is valid
    if (array_key_exists($page, $pages)) {
        // Include the corresponding file
        include $pages[$page];
    } else {
        // Page not found, handle the error or redirect to a default page
        echo 'Page not found';
    }
?>