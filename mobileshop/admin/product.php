<?php 
include('header.php');

$prod_id='';
$prod_id_error='';
$cat_id='';
$cate_id_error='';
$prod_name='';
$prod_name_error='';
$prod_price='';
$prod_price_error='';
$prod_image='';
$prod_image_error='';
$edit_prod_image='';
$prod_desc='';
$prod_desc_error='';
$prod_status='';
$prod_status_error='';
$action = '';
$id = '';
$formaction = '';
$submitedmsg = '';

$search_url = isset($_GET['search']) && $_GET['search'] ? 'search='.$_GET['search'].'&' : '';   
$search_value = isset($_GET['search']) && $_GET['search'] ? $_GET['search'] : ''; 

$column = isset($_GET['column']) && $_GET['column'] ? $_GET['column'] : 'prod_id';
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';
$asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';


if(isset($_GET['action'])){
	$action = $_GET['action'];
	$formaction = 'action=' . $action;
}

if(isset($_GET['id'])){
	$id = $_GET['id'];
}

// msg display.........
if(isset($_GET["msg"]) && $_GET["msg"] == 'I'){
	$submitedmsg = 'Record Added';
	// header("refresh:2");
}
if(isset($_GET["msg"]) && $_GET["msg"] == 'U'){
	$submitedmsg = 'Record Updated';
	// header("refresh:2");
}
if(isset($_GET["msg"]) &&  $_GET["msg"] == 'D'){
	$submitedmsg = 'Record Deleted';
	// header("refresh:2");
}
//End msg..........

// All query's.....................
$listing_sql = "SELECT * FROM `product_master` WHERE (product_master.prod_name like '%" . $search_value . "%' OR product_master.prod_desc like '%" . $search_value . "%') ORDER BY $column  $sort_order  LIMIT  $offset, $total_records_per_page";
$listing_result = mysqli_query($conn, $listing_sql);
// End query's............

// start edit form........
if($id != ''&& $action == 'Edit'){
	$formaction .= '&id=' . $id;
	// get edit records in form of selected id
	$update_query = "SELECT * FROM `product_master` WHERE `prod_id` =  $id ";
	$update_result = mysqli_query($conn, $update_query);
    
	$prod_row = $update_result->fetch_assoc();
    
    $cat_id = $prod_row['cat_id'];
	$prod_name   = $prod_row['prod_name'];
	$prod_price   = $prod_row['prod_price'];
	$edit_prod_image  = $prod_row['prod_image'];
	$prod_desc   = $prod_row['prod_desc'];
	$prod_status = $prod_row['prod_status'];
}
// End edit form..........

// insert in DB with validation............
if (isset($_POST['submit'])){
	$error = 'false';
    
    $cat_id = $_POST['cat_id'];
	$prod_name = $_POST['prod_name'];
	$prod_price = $_POST['prod_price'];
	$prod_image = $_FILES['prod_image'];
	$prod_desc = $_POST['prod_desc'];
	$prod_status = $_POST['prod_status'];
     
    
	if($prod_name == ''){
		$prod_name_error = 'Please enter Name';
		$error = 'true';
	}
	if($prod_price == ''){
		$prod_price_error = 'Please enter Price';
		$error = 'true';
	}
	if($prod_image == '' && $edit_prod_image == ''){
		$prod_image_error = 'Please choosed image';
		$error = 'true';
	}
	if($prod_desc == ''){
		$prod_desc_error = 'Please enter Description';
		$error = 'true';
	}
	if($prod_status == ''){
		$prod_status_error = 'Please select status';
		$error = 'true';
	}
    //for image..
        if(isset($_FILES["prod_image"]) && $_FILES["prod_image"]["name"]!='')
        { 
			$prod_image = time().$_FILES["prod_image"]["name"];

			$tempname = $_FILES["prod_image"]["tmp_name"];
			$folder =SITE_ROOT_IMG.'/product/'.$prod_image;
				
				if(move_uploaded_file($tempname,$folder)){ // Image uploaded in folder ; 
					}else{ echo "<h3>  Failed to upload image!</h3>";
	        	}
			if($id!='')
			{
				$editimage = SITE_ROOT_IMG.'/product/'.$edit_prod_image; 
				if(file_exists($editimage)){ unlink($editimage); //echo "File Successfully update."; 
			     }
		    } 
		}else{
			$prod_image = $edit_prod_image;
		}
		
	//End image..

	if ($error == 'false')
	{
		if ($_POST["submit"] == 'Add')
		{
			$ins_query = "INSERT INTO `product_master` (`cat_id`,`prod_name`,`prod_price`,`prod_image`,`prod_desc`,`prod_status`) VALUES ('" . $cat_id . "','" . $prod_name . "','" . $prod_price . "','" . $prod_image . "','" . $prod_desc . "','" . $prod_status . "')";
			$ins_result = mysqli_query($conn, $ins_query);
			header("location:".SITE_URL_ADMIN."./product.php?msg=I");
		}
		elseif($_POST["submit"] == 'Edit')
		{
			$prod_update = "UPDATE `product_master` SET `prod_name`='" . $prod_name . "',`prod_price`='" . $prod_price . "',`prod_image`='" . $prod_image . "', `prod_desc`='" . $prod_desc . "', `prod_status`='" . $prod_status . "'  WHERE prod_id='$id'";
			$prod_result = mysqli_query($conn, $prod_update);
           	header("location:".SITE_URL_ADMIN."./product.php?msg=U");   
		}
	}
}
// End insert....

//Delete ....
if($id != '' && $action == 'Delete'){
	$delete_prod_query  = "SELECT * FROM `PRODUCT_master` WHERE `prod_id` =  $id ";
	$delete_prod_result = mysqli_query($conn, $delete_prod_query);
	$delete_prod_row_count   = mysqli_num_rows($delete_prod_result);
	$delete_prodimage_row = $delete_prod_result->fetch_assoc();
        
	if($delete_prod_row_count > 0){
		// $path ='./Cateimage/'.$delete_catimage_row['cat_image']; 
		$path =SITE_ROOT_IMG.'/product/'.$delete_prodimage_row['prod_image'];
		if(file_exists($path)){ unlink($path); //echo "File Successfully Delete.";
        	} 
		$listing_delete  = mysqli_query($conn, "DELETE  FROM  `product_master` WHERE `prod_id`= $id");
		header("location:".SITE_URL_ADMIN."./product.php?msg=D");   //right.....
	}
}
//End delete............

//category dropdown......
	$category_selectname = "SELECT * FROM `category_master` where `cat_status`='Active'";
	$result = mysqli_query($conn, $category_selectname);
//End.......
?>
<tr><td width="100%">
	<table width="100%" border="0">
		<tr><td width="100%" align="center"><h3>All products</h3></td></tr>

		<?php if ($action == 'Add' || $action == 'Edit') { ?>

		<tr><td>
			<form method="POST" action="product.php?<?php echo $formaction; ?>" enctype="multipart/form-data">
				<table width="50%" border="1" cellpadding="5" align="center">
					<tr><td colspan="2" align="right"><a href="<?php echo SITE_URL_ADMIN.'/product.php';?>"class="btn btn-outline-success">Back</a></td></tr>
                    <tr>
                        <th>Category</th>
                        <td>
                            <select name="cat_id">
                                <option value="0">--select--</option>
                            <?php if($result->num_rows > 0){
                               		while ($listing_row = $result->fetch_assoc()){ 
                               			?>
                                <option value="<?php echo $listing_row['cat_id']; ?>" <?php if ($cat_id == $listing_row['cat_id']){ echo "selected"; }
                                                    ?>><?php echo $listing_row['cat_name']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
					<tr>
						<th>Name</th>
						<td><input type="text" name="prod_name" value="<?php echo $prod_name; ?>"><br><?php echo $prod_name_error; ?></td>
					</tr>
					<tr>
						<th>Price</th>
						<td><input type="text" name="prod_price" value="<?php echo $prod_price; ?>"><br><?php echo $prod_price_error; ?></td>
					</tr>
					<tr>
						<th>Image</th>
						<td><input type="file" name="prod_image" value=""><br><?php echo $prod_image_error; ?>
						<?php if($action == 'Edit'){ ?><img src="<?php echo SITE_URL_IMG.'/product/'.$listing_row['prod_image']; ?>" height="100" width="100"><?php } ?></td>
					</tr>
					<tr>
						<th>Description</th>
						<td><textarea name="prod_desc"><?php echo $prod_desc; ?></textarea><br><?php echo $prod_desc_error; ?></td>
					</tr>
					<tr>
						<th>Status</th>
						<td><input type="radio" name="prod_status" value="Active" <?php echo "checked $prod_status == 'Active'" ; ?>>Active
							<input type="radio" name="prod_status" value="Deactive" <?php if($prod_status == 'Deactive'){ echo "checked"; } ?>>Deactive
						</td>
					</tr>
					<tr>
						<th>Gallery image</th>
	                    <?php   
	                        if ($result->num_rows > 0){
	                            while ($row = $result->fetch_assoc()){
	                        ?>
							<td><input type="file" name="prodmt_glrimg" class="prodmt_glrimg"></td>
							<?php
								}
							}
						?>
						<td><button type="button" id="Addimage" onclick="addimage()">Add++</button></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><input type="submit" name="submit" value="<?php echo $action; ?>"></td>
					</tr>
				</table><br>
			</form>
		</td></tr>

		<?php }else {  ?>

		<tr><td align="right"><?php if ($submitedmsg != '') { echo $submitedmsg; } ?></td></tr>

        <tr><td>
			<form method="get" action="product.php">
           <input type="text" placeholder="Search.." name="search" value="<?php echo $search_value; ?>"><button type="submit" id="search_btn" class="btn btn-outline-warning">Search</button>
		</form>
		</td></tr>
        
		<tr><td align="right"><a href="<?php echo SITE_URL_ADMIN.'/product.php?action=Add';?>">Add+</a></td></tr>
		<tr><td>
			<table width="100%" border="1">
				<tr>
					<td><a href="product.php?<?php echo $search_url; ?>column=prod_id&order=<?php echo $asc_or_desc; ?>"><b>ID</b></td>
					<td><b>Category id</b></td>
					<td><a href="product.php?<?php echo $search_url; ?>column=prod_name&order=<?php echo $asc_or_desc; ?>"><b>Name</b></td>
					<td><a href="product.php?<?php echo $search_url; ?>column=prod_price&order=<?php echo $asc_or_desc; ?>"><b>Price</b></td>
					<td><b>Image</b></td>
					<td><a href="product.php?<?php echo $search_url; ?>column=prod_desc&order=<?php echo $asc_or_desc; ?>"><b>Description</b></td>
					<td><a href="product.php?<?php echo $search_url; ?>column=prod_status&order=<?php echo $asc_or_desc; ?>"><b>Status</b></td>
					<td><b>Action</b></td>
				</tr>
				<?php
					if($listing_result->num_rows > 0){
					 	while($listing_row = $listing_result->fetch_assoc()){ ?>
				<tr>
					<td><?php echo $listing_row['prod_id']; ?></td>
					<td><?php echo $listing_row['cat_id']; ?></td>
					<td><?php echo $listing_row['prod_name']; ?></td>
					<td><?php echo $listing_row['prod_price']; ?></td>
					<td><img src="<?php echo SITE_URL_IMG.'/product/'.$listing_row['prod_image']; ?>" height="100" width="100"></td>
					<td><?php echo $listing_row['prod_desc']; ?></td>
					<td><?php echo $listing_row['prod_status']; ?></td>
					<td>
						<a class="btn btn-info" href="product.php?action=Edit&id=<?php echo $listing_row['prod_id']; ?>">Edit</a>&nbsp;
						<a class="btn btn-danger" onclick="return deletelist();" value="Delete" href="product.php?action=Delete&id=<?php echo $listing_row['prod_id']; ?>">Delete</a>
					</td>
				</tr>
				<?php } } ?>
			</table>
			
		</td></tr>
		<tr><td> <?php include('pagination.php')?></td></tr>
		<?php  	
		}
		?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js"></script>
	    <script>
		    function addimage(){
                var html = '<td><input type="file" name="prodmt_glrimg" class="prodmt_glrimg">'
                <?php
                if ($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()){
                   ?> 
                        '<td><input type="file" name="prodmt_glrimg" class="prodmt_glrimg">'
                   <?php
                    }
                }
                ?> 
            }
	    </script>
	</table>
</td></tr> 
<?php include('footer.php'); ?>   
    
