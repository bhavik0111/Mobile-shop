<?php
include('header.php');
$cat_name='';
$cat_name_error='';
$cat_image='';
$cat_image_error='';
$edit_cat_image='';
$cat_desc='';
$cat_desc_error='';
$cat_status='';
$cat_status_error='';
$action = '';
$id = '';
$formaction = '';
$submitedmsg = '';

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
}
if(isset($_GET["msg"]) && $_GET["msg"] == 'U'){
	$submitedmsg = 'Record Updated';
}
if(isset($_GET["msg"]) && $_GET["msg"] == 'D'){
	$submitedmsg = 'Record Deleted';
}
//End msg..........

// All query's.....................
$listing_sql = "SELECT * FROM `category_master` WHERE `cat_id` = cat_id ";
$listing_result = mysqli_query($conn, $listing_sql);
// End query's............

// start edit form........
 if($id != ''&& $action == 'Edit'){
	$formaction .= '&id=' . $id;
	// get edit records in form of selected id
	$update_query = "SELECT * FROM `category_master` WHERE `cat_id` =  $id ";
	$update_result = mysqli_query($conn, $update_query);
    
	$cat_row = $update_result->fetch_assoc();
	$cat_name   = $cat_row['cat_name'];
	$edit_cat_image  = $cat_row['cat_image'];
	$cat_desc   = $cat_row['cat_desc'];
	$cat_status = $cat_row['cat_status'];

	// $path ='./Cateimage/'.$cat_row['cat_image']; 
	// 	if(file_exists($path)){ unlink($path); //echo "File Successfully update.";
    // 	} 
}
// End edit form..........

// insert in DB with validation............
if (isset($_POST['submit'])) {
	$error = 'false';

	$cat_name = $_POST['cat_name'];
	$cat_image = $_FILES['cat_image'];
	$cat_desc = $_POST['cat_desc'];
	$cat_status = $_POST['cat_status'];

	if($cat_name == ''){
		$cat_name_error = 'Please enter Name';
		$error = 'true';
	}
	if($cat_image == '' && $edit_cat_image == ''){
		$cat_image_error = 'Please choosed image';
		$error = 'true';
	}
	if($cat_desc == ''){
		$cat_desc_error = 'Please enter Description';
		$error = 'true';
	}
	if($cat_status == ''){
		$cat_status_error = 'Please select status';
		$error = 'true';
	}

    //for image..
        if(isset($_FILES["cat_image"]) && $_FILES["cat_image"]["name"]!='')
        { 
			$cat_image = time().$_FILES["cat_image"]["name"];

			$tempname = $_FILES["cat_image"]["tmp_name"];
			// $folder = "./Cateimage/".$cat_image;
			$folder =SITE_ROOT_IMG.'/category/'.$cat_image;
				
				if(move_uploaded_file($tempname,$folder)){ // Image uploaded in folder ; 
					}else{ echo "<h3>  Failed to upload image!</h3>";
	        	}
			if($id!='')
			{
				$editimage = SITE_ROOT_IMG.'/category/'.$edit_cat_image; 
				if(file_exists($editimage)){ unlink($editimage); //echo "File Successfully update."; 
			     }
		    } 
		}else{
			$cat_image = $edit_cat_image;
		}
		
	//End image..

	if ($error == 'false')
	{
		if ($_POST["submit"] == 'Add')
		{
			$ins_query = "INSERT INTO `category_master` (`cat_name`,`cat_image`,`cat_desc`,`cat_status`) VALUES ('" . $cat_name . "','" . $cat_image . "','" . $cat_desc . "','" . $cat_status . "')";
			$ins_result = mysqli_query($conn, $ins_query);
			header("location:".SITE_URL_ADMIN."./category.php?msg=I");
		}
		elseif($_POST["submit"] == 'Edit')
		{
			$cat_update = "UPDATE `category_master` SET `cat_name`='" . $cat_name . "',`cat_image`='" . $cat_image . "', `cat_desc`='" . $cat_desc . "', `cat_status`='" . $cat_status . "'  WHERE cat_id='$id'";
			$cat_result = mysqli_query($conn, $cat_update);
           	header("location:".SITE_URL_ADMIN."./category.php?msg=U");   //right...
		}
	}
}
// End insert....

//Delete ....
if($id != '' && $action == 'Delete'){
	$delete_cat_query  = "SELECT * FROM `category_master` WHERE `cat_id` =  $id ";
	$delete_cat_result = mysqli_query($conn, $delete_cat_query);
	$delete_cat_row_count   = mysqli_num_rows($delete_cat_result);
	$delete_catimage_row = $delete_cat_result->fetch_assoc();
        
	if($delete_cat_row_count > 0){
		// $path ='./Cateimage/'.$delete_catimage_row['cat_image']; 
		$path =SITE_ROOT_IMG.'/category/'.$delete_catimage_row['cat_image'];
		if(file_exists($path)){ unlink($path); //echo "File Successfully Delete.";
        	} 
		$listing_delete  = mysqli_query($conn, "DELETE  FROM  `category_master` WHERE `cat_id`= $id");
		header("location:".SITE_URL_ADMIN."./category.php?msg=D");   //right.....
	}
}
//End delete............
?>
<tr><td width="100%">
	<table width="100%" border="0">
		<tr><td width="100%" align="center"><h3>All categories</h3></td></tr>

		<?php if ($action == 'Add' || $action == 'Edit') { ?>

		<tr><td>
			<form method="POST" action="category.php?<?php echo $formaction; ?>" enctype="multipart/form-data">
				<table width="50%" border="1" cellpadding="5" align="center">
					<tr><td colspan="2" align="right"><a href="<?php echo SITE_URL_ADMIN.'/category.php';?>" class="btn btn-outline-success">Back</a></td></tr>

					<tr>
						<th>Name</th>
						<td><input type="text" name="cat_name" value="<?php echo $cat_name; ?>"><br><?php echo $cat_name_error; ?></td>
					</tr>
					<tr>
						<th>Image</th>
						<td><input type="file" name="cat_image" value=""><br><?php echo $cat_image_error; ?></td>
					</tr>
					<tr>
						<th>Description</th>
						<td><textarea name="cat_desc"><?php echo $cat_desc; ?></textarea><br><?php echo $cat_desc_error; ?></td>
					</tr>
					<tr>
						<th>Status</th>
						<td><input type="radio" name="cat_status" value="1" <?php echo "checked $cat_status == '1'" ; ?>>Active
							<input type="radio" name="cat_status" value="0" <?php if($cat_status == '0'){ echo 'checked="checked"'; } ?>>Deactive
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
           <input type="text" placeholder="Search.." name="search"><button type="submit" id="search_btn" class="btn btn-outline-warning">Search</button>
        </td></tr>
        
		<tr><td align="right"><a href="<?php echo SITE_URL_ADMIN.'/category.php?action=Add';?>">Add+</a></td></tr>
		<tr><td>
			<table width="100%" border="1">
				<tr>
					<td>ID</td>
					<td>Name</td>
					<td>Image</td>
					<td>Description</td>
					<td>Status</td>
					<td>Action</td>
				</tr>
				<?php
					if($listing_result->num_rows > 0){
					 	while($listing_row = $listing_result->fetch_assoc()){ ?>
				<tr>
					<td><?php echo $listing_row['cat_id']; ?></td>
					<td><?php echo $listing_row['cat_name']; ?></td>
					<td><img src="<?php echo SITE_URL_IMG.'/category/'.$listing_row['cat_image']; ?>" height="100" width="100"></td>
					<td><?php echo $listing_row['cat_desc']; ?></td>
					<td><?php echo $listing_row['cat_status']; ?></td>
					<td>
						<a class="btn btn-info" href="category.php?action=Edit&id=<?php echo $listing_row['cat_id']; ?>">Edit</a>&nbsp;
						<a class="btn btn-danger" value="Delete" href="category.php?action=Delete&id=<?php echo $listing_row['cat_id']; ?>">Delete</a>
					</td>
				</tr>
				<?php } } ?>
			</table>
		</td></tr>
		<tr><td>Pagination</td></tr>
		<?php  	
		}
		?>

	</table>
</td></tr>
<?php include('footer.php'); ?>

