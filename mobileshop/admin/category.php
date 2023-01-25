<?php
 include 'connect.php';

$cat_name='';
$cat_name_error='';
$cat_image='';
$cat_image_error='';
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
// End query's.....................

// start edit form........
/* if($id != ''){
	$formaction .= '&id=' . $id;
	// get edit records in form of selected id
	$update_query = "SELECT * FROM `category_master` WHERE `cat_id` =  $id ";
	$update_result = mysqli_query($conn, $update_query);

	$cat_row = $update_result->fetch_assoc();
	$cat_name   = $cat_row['cat_name'];
	$cat_image  = $cat_row['cat_image'];
	$cat_desc   = $cat_row['cat_desc'];
	$cat_status = $cat_row['cat_status'];
	
}*/
// End edit form..........

// insert in DB with validation..........................
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
	if($cat_image == ''){
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
		$cat_image = $_FILES["cat_image"]["name"];
		$tempname = $_FILES["cat_image"]["tmp_name"];
		$folder = "./Cateimage/" . $cat_image;
			// Now move uploaded image into the folder: Cateimage
			if (move_uploaded_file($tempname,$folder)){ // echo "<h3>  Image uploaded successfully!</h3>"; 
				}else{ echo "<h3>  Failed to upload image!</h3>";
			}
	//End image..

	if ($error == 'false'){
		if ($_POST["submit"] == 'Add'){
			$ins_query = "INSERT INTO `category_master` (`cat_name`,`cat_image`,`cat_desc`,`cat_status`) VALUES ('" . $cat_name . "','" . $cat_image . "','" . $cat_desc . "','" . $cat_status . "')";

			$ins_result = mysqli_query($conn, $ins_query);
			header("location:category.php?msg=I");
		}elseif($_POST["submit"] == 'Edit'){
			$cat_update = "UPDATE `category_master` SET `cat_name`='" . $cat_name . "', `cat_image`='" . $cat_image . "', `cat_desc`='" . $cat_desc . "', `cat_status`='" . $cat_status . "' ";
			$cat_result = mysqli_query($conn, $cat_update);

			header("location:category.php?msg=U");
		}
	}
}
// End insert....

//Delete ....
if($id != '' && $action == 'Delete'){
	$delete_cat_query  = "SELECT * FROM `category_master` WHERE `cat_id` =  $id ";
	$delete_cat_result = mysqli_query($conn, $delete_cat_query);
	$delete_cat_row    = mysqli_num_rows($delete_cat_result);
        
	if ($delete_cat_row > 0){
		$listing_delete  = mysqli_query($conn, "DELETE  FROM  `category_master` WHERE `cat_id`= $id");
		header("location:category_master.php?msg=D");
	}
}
//End delete.........................
?>
<!DOCTYPE html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="d-flex flex-column h-100">
	<?php if ($action == 'Add' || $action == 'Edit') { ?>
		<div class="category.php" align="right">
			<a href="category.php" class="btn btn-outline-success">Back</a>
		</div>
		<div class="header text-center">
			<h2>Category</h2>
		</div>
		<form method="POST" action="category.php?<?php echo $formaction; ?>" enctype="multipart/form-data">
			<table align="center" border="1" cellspacing="0" cellpadding="15">
				<tr>
					<th>Name</th>
					<td><input type="text" name="cat_name" value="<?php echo $cat_name; ?>"><br><?php echo $cat_name_error; ?></td>
				</tr>
				<tr>
					<th>Image</th>
					<td><input type="file" name="cat_image" value="<?php echo $cat_image; ?>"><br><?php echo $cat_image_error; ?></td>
				</tr>
				<tr>
					<th>Description</th>
					<td><textarea name="cat_desc"><?php echo $cat_desc; ?></textarea><br><?php echo $cat_desc_error; ?></td>
				</tr>
				<tr>
					<th>Status</th>
					<td><input type="radio" name="cat_status" value="1" <?php if ($cat_status == '1') {
																		echo 'checked="checked"';
																	} ?>>Active
						<input type="radio" name="cat_status" value="0" <?php if ($cat_status == '0') {
																		echo 'checked="checked"';
																	} ?>>Deactive
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" name="submit" value="<?php echo $action; ?>"></td>
				</tr>
			</table>
		</form>
	<?php } else { ?>

		<head>
			<?php if ($submitedmsg != '') {
				echo '<div class="container" align="left">' . $submitedmsg . '</div>';
			} ?>

			<div class="container" align="right">
				<a href="category.php?action=Add" class="btn btn-outline-success">Add+</a>
			</div>
			<div class="container" align="left">
				<h2 class="pb-xxl-4">All categories</h2>
			</div>
		</head>

		<body>
			<table class="table">
				<thead>
					<th>cat_id</th>
					<th>cat_name</th>
					<th>cat_image</th>
					<th>cat_desc</th>
					<th>cat_status</th>
					<th>action</th>
				</thead>
				<?php
				if ($listing_result->num_rows > 0) {
					while ($listing_row = $listing_result->fetch_assoc()) { ?>
						<tr>
							<td><?php echo $listing_row['cat_id']; ?></td>
							<td><?php echo $listing_row['cat_name']; ?></td>
							<td><img src="<?php echo './Cateimage/',$listing_row['cat_image']; ?>" height="100" width="100"></td>
							<td><?php echo $listing_row['cat_desc']; ?></td>
							<td><?php echo $listing_row['cat_status']; ?></td>
							<td>
								<a class="btn btn-info" href="category.php?action=Edit&id=<?php echo $listing_row['cat_id']; ?>">Edit</a>&nbsp;
								<a class="btn btn-danger" value="Delete" href="category.php?action=Delete&id=<?php echo $listing_row['cat_id']; ?>">Delete</a>
							</td>
						</tr>
				<?php }
				}
				?>
		</body>
	<?php
	}
	?>
</body>

</html>
<?php
// include('footer.php');
?>
