<?php
  $servername = "localhost:3307";
  $username = "root";
  $password = "0102bryan";
  $dbname = "hrms";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>