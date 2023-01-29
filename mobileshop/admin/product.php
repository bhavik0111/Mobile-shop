<?php 
include('header.php');

$prod_id='';
$prod_id_error='';
$cat_id='';
$cate_id_error='';
$prod_image='';
$prod_image_error='';
$prod_name='';
$prod_name_error='';
$prod_desc='';
$prod_desc_error='';
$prod_status='';
$prod_status_error='';
$action = '';
$id = '';
$formaction = '';
$submitedmsg = '';

$column = isset($_GET['column']) && $_GET['column'] ? $_GET['column'] : 'prod_id';
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';
$asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';

$search_url = isset($_GET['search']) && $_GET['search'] ? 'search='.$_GET['search'].'&' : '';   
$search_value = isset($_GET['search']) && $_GET['search'] ? $_GET['search'] : ''; 
                                                              

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
	$prod_name   = $prod_row['prod_name'];
	$edit_prod_image  = $prod_row['prod_image'];
	$prod_desc   = $prod_row['prod_desc'];
	$prod_status = $prod_row['prod_status'];

	// $path ='./Cateimage/'.$cat_row['cat_image']; 
	// 	if(file_exists($path)){ unlink($path); //echo "File Successfully update.";
    // 	} 
}
// End edit form..........

// insert in DB with validation............
if (isset($_POST['submit'])) {
	$error = 'false';

	$prod_name = $_POST['prod_name'];
	$prod_image = $_FILES['prod_image'];
	$prod_desc = $_POST['prod_desc'];
	$prod_status = $_POST['prod_status'];

	if($prod_name == ''){
		$prod_name_error = 'Please enter Name';
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
			$ins_query = "INSERT INTO `product_master` (`prod_name`,`prod_image`,`prod_desc`,`prod_status`) VALUES ('" . $prod_name . "','" . $prod_image . "','" . $prod_desc . "','" . $prod_status . "')";
			$ins_result = mysqli_query($conn, $ins_query);
			header("location:".SITE_URL_ADMIN."./product.php?msg=I");
		}
		elseif($_POST["submit"] == 'Edit')
		{
			$prod_update = "UPDATE `product_master` SET `prod_name`='" . $prod_name . "',`prod_image`='" . $prod_image . "', `prod_desc`='" . $prod_desc . "', `prod_status`='" . $prod_status . "'  WHERE prod_id='$id'";
			$prod_result = mysqli_query($conn, $prod_update);
           	header("location:".SITE_URL_ADMIN."./product.php?msg=U");   //right...
		}
	}
}
// End insert....
?>
<tr><td width="100%">
	<table width="100%" border="0">
		<tr><td width="100%" align="center"><h3>All products</h3></td></tr>

		<?php if ($action == 'Add' || $action == 'Edit') { ?>

		<tr><td>
			<form method="POST" action="product.php?<?php echo $formaction; ?>" enctype="multipart/form-data">
				<table width="50%" border="1" cellpadding="5" align="center">
					<tr><td colspan="2" align="right"><a href="<?php echo SITE_URL_ADMIN.'/product.php';?>" class="btn btn-outline-success">Back</a></td></tr>
                    
                    <?php 
                        $category_selectname = "SELECT * FROM `category_master` WHERE `cat_id`='. $id .' AND `cat_status`='Active'";
                        $result = mysqli_query($conn, $category_selectname);
                    ?>
                    <tr>
                        <th>Category</th>
                        <td>
                            <select name="cat_id" value="<?php echo $cat_id; ?>"><br><?php echo $cat_id_error; ?>
                                <option value="0">--select--</option>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($listing_row = $result->fetch_assoc()) 
                                    {
                                        ?>
                                <option value="<?php echo $listing_row['cat_id']; ?>" <?php if ($cat_name == $listing_row['cat_id']){ echo "selected"; }
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
						<th>Image</th>
						<td><input type="file" name="prod_image" value=""><br><?php echo $prod_image_error; ?></td>
					</tr>
					<tr>
						<th>Description</th>
						<td><textarea name="prod_desc"><?php echo $prod_desc; ?></textarea><br><?php echo $prod_desc_error; ?></td>
					</tr>
					<tr>
						<th>Status</th>
						<td><input type="radio" name="prod_status" value="1" <?php echo "checked $prod_status == '1'" ; ?>>Active
							<input type="radio" name="prod_status" value="0" <?php if($prod_status == '0'){ echo 'checked="checked"'; } ?>>Deactive
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center"><input type="submit" name="submit" value="<?php echo $action; ?>"></td>
					</tr>
				</table>
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
					<td><a href="product.php?<?php echo $search_url; ?>column=prod_id&order=<?php echo $asc_or_desc; ?>">ID</td>
					<td><a href="product.php?<?php echo $search_url; ?>column=prod_name&order=<?php echo $asc_or_desc; ?>">Name</td>
					<td>Image</td>
					<td><a href="product.php?<?php echo $search_url; ?>column=prod_desc&order=<?php echo $asc_or_desc; ?>">Description</td>
					<td><a href="product.php?<?php echo $search_url; ?>column=prod_status&order=<?php echo $asc_or_desc; ?>">Status</td>
					<td>Action</td>
				</tr>
				<?php
					if($listing_result->num_rows > 0){
					 	while($listing_row = $listing_result->fetch_assoc()){ ?>
				<tr>
					<td><?php echo $listing_row['prod_id']; ?></td>
					<td><?php echo $listing_row['prod_name']; ?></td>
					<td><img src="<?php echo SITE_URL_IMG.'/product/'.$listing_row['prod_image']; ?>" height="100" width="100"></td>
					<td><?php echo $listing_row['prod_desc']; ?></td>
					<td><?php echo $listing_row['prod_status']; ?></td>
					<td>
						<a class="btn btn-info" href="product.php?action=Edit&id=<?php echo $listing_row['prod_id']; ?>">Edit</a>&nbsp;
						<a class="btn btn-danger" value="Delete" href="product.php?action=Delete&id=<?php echo $listing_row['prod_id']; ?>">Delete</a>
					</td>
				</tr>
				<?php } } ?>
			</table>
		</td></tr>
		<tr><td> <?php include('pagination.php')?></td></tr>
		<?php  	
		}
		?>

	</table>
</td></tr> 
<?php include('footer.php'); ?>   
    
