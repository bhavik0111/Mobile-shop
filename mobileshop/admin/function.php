<?php

  $page_no = 0;
  $total_no_of_pages = 0;

  if(isset($_GET['page_no']) && $_GET['page_no'] != ''){
      $page_no = $_GET['page_no'];
    }else{
      $page_no = 1;
  }
  $total_records_per_page = 5;
  $offset = ($page_no - 1) * $total_records_per_page;
  $previous_page = $page_no - 1;
  $next_page = $page_no + 1;
  $adjacents = '2';

function check_login_status(){

    if(isset($_SESSION['logged_in'])){
      return $_SESSION['logged_in'];
    }
    return false;
}
?>