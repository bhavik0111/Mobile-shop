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
define("SITE_URL", "http://192.168.0.1/test/bhavik/mobileshop");
define("SITE_URL_ADMIN", "http://192.168.0.1/test/bhavik/mobileshop/admin");
define("SITE_ROOT_URL", $_SERVER['DOCUMENT_ROOT']."/test/bhavik/mobileshop"); 
define("SITE_ROOT_IMG", $_SERVER['DOCUMENT_ROOT']."/test/bhavik/mobileshop/images"); 
define("SITE_URL_IMG", "http://192.168.0.1/test/bhavik/mobileshop/images");   //listing image path
?>