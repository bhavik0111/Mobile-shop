<?php 

$page_no = 0;
$total_no_of_pages = 0;

if (isset($_GET['page_no']) && $_GET['page_no']!="") 
{
    $page_no = $_GET['page_no'];
} 
else 
{
    $page_no = 1;
}
$total_records_per_page = 5;
$offset = ($page_no-1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";
$result_count = mysqli_query($conn, "SELECT COUNT(*) As total_records FROM `category`");
$total_records = mysqli_fetch_array($result_count);
$total_records = $total_records['total_records'];
$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1;


?>
 <!-- ...................pagination....................... -->

<!-- <nav aria-label="Page navigation example">
	<ul class="pagination">

		<?php if($page_no > 1){ // echo "<li> <a href='?page_no=1'>First Page</a></li>";
		 }?>

		<li class="page-item" <?php if($page_no <= 1) { echo "class='disabled'"; } ?>>
			<a class="page-link" <?php if($page_no > 1) {
			echo "href='?page_no=$previous_page'";} ?>>Previous</a>
		</li>
			<?php
/*				if ($total_no_of_pages <= 10){     
					for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
						if ($counter == $page_no){
							echo "<li class='page-item active'><a class='page-link'>$counter</a></li>"; 
							}else{
							echo "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
						}
					}
				}
			?>
		<li class="page-item" <?php if($page_no >= $total_no_of_pages){
			echo "class='disabled'";} ?>>
			<a class="page-link" <?php if($page_no < $total_no_of_pages){
			echo "href='?page_no=$next_page'";} ?>>Next</a>
		</li>

		<?php /* if($page_no < $total_no_of_pages){
		//  echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
		}*/ ?>
	</ul>
</nav>
 -->

