<?php

 $dbhost ="localhost";
 $dbusername ="root";
 $dbpassword ="";
 $dbname ="test_bhavikshop";

 $conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);

  if(!$conn)
  {
    echo "database could not connect" .mysqli_error($conn);
  }
?>