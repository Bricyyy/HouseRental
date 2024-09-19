<?php
  $servername = "localhost:3307";
  $username = "root";
  $password = "0102bryan";
  $dbname = "hrms";

  // Create connection
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
?>