<?php
  // MYSQL Settings 

  $DB_HOST = "localhost:3308";
  $DB_USER = "root";
  $DB_PASSWORD = "";
  $DB_NAME = "online_bus_dispatch";
  
  // Database connection
  $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
  
  if(!$conn)
    die(" - Database Connection Error : ".mysqli_connect_error());
?>