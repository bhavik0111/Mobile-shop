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



$page_no = 0;
$total_no_of_pages = 0;

if(isset($_GET['page_no']) && $_GET['page_no'] != ''){
    $page_no = $_GET['page_no'];
  }else{
      $page_no = 1;
}
$total_records_per_page = 10;
$offset = ($page_no - 1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = '2';
$result_count = mysqli_query($conn,'SELECT COUNT(*) As total_records FROM `category_master`');
$total_records = mysqli_fetch_array($result_count);
$total_records = $total_records['total_records'];
$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1;

?>
