<?php
include('header.php');

 if(!($_SESSION["usr_role"] == 'Admin')){
	header("location:index.php");
 }
$usr_name = '';
$usr_name_error = '';
$usr_email = '';
$usr_email_error = '';
$usr_password = '';
$usr_password_error = '';
$edit_usr_password = '';
$usr_role = '';
$usr_role_error = '';
$usr_block = '';
$usr_block_error = '';
$status = '';
$status_error = '';
$action = '';
$id = '';
$formaction = '';
$submitedmsg = '';

$search_url = isset($_GET['search']) && $_GET['search'] ? 'search='.$_GET['search'].'&' : '';   
$search_value = isset($_GET['search']) && $_GET['search'] ? $_GET['search'] : ''; 

$result_count = mysqli_query($conn,'SELECT COUNT(*) As total_records FROM `user_master`');
$total_records = mysqli_fetch_array($result_count);
$total_records = $total_records['total_records'];
$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1;

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
	$listing_sql = "SELECT * FROM `user_master` WHERE (user_master.usr_name like '%" . $search_value . "%' OR user_master.usr_email like '%" . $search_value . "%') LIMIT  $offset, $total_records_per_page";

	$listing_result = mysqli_query($conn, $listing_sql);
// End query's.....................

// start edit form........
if($id != ''){
	$formaction .= '&id=' . $id;
    	//Get edit records in form of selected id
	$update_query = "SELECT * FROM `user_master` WHERE `usr_id` =  $id ";
	$update_result = mysqli_query($conn, $update_query);

	$user_row = $update_result->fetch_assoc();
	$usr_name = $user_row['usr_name'];
	$usr_email = $user_row['usr_email'];
	$edit_usr_password = $user_row['usr_password'];
	$usr_block = $user_row['usr_block'];
	$usr_role = $user_row['usr_role'];
	$status = $user_row['status'];
}
// End edit form..........

// insert in DB with validation..........................
if (isset($_POST['submit'])) {
	$error = 'false';

	$usr_name = $_POST['usr_name'];
	$usr_email = $_POST['usr_email'];
	$usr_password = $_POST['usr_password'];
	$usr_role = $_POST['usr_role'];
	$usr_block = $_POST['usr_block'];
	$status = $_POST['status'];

	if($usr_name == ''){
		$usr_name_error = 'Please enter Name';
		$error = 'true';
	}
	if($usr_email == ''){
		$usr_email_error = 'Please enter Email';
		$error = 'true';
	}
	if($usr_password == '' && $edit_usr_password == ''){
		$usr_password_error = 'Please enter Password';
		$error = 'true';
	}
	if($usr_role == ''){
		$usr_role_error = 'Please select Role';
		$error = 'true';
	}
	if($usr_block == ''){
		$usr_block_error = 'Please enter this field';
		$error = 'true';
	}
	if($status == ''){
		$status_error = 'Please enter status';
		$error = 'true';
	}
	// check exist email
	if($usr_email != '')
	{
		if($id != '')
		{
			$fiend_email = mysqli_query($conn, "SELECT * FROM `user_master` WHERE `usr_email`='".$usr_email."' AND `usr_id` !='".$id."' ");
			if(mysqli_num_rows($fiend_email)>0){
				$usr_email_error = "email id already exists";
				$error = 'true';
			}
		}else{
			$fiend_email = mysqli_query($conn, "SELECT * FROM `user_master` WHERE `usr_email` ='".$usr_email."' ");
			if(mysqli_num_rows($fiend_email) > 0){
				$usr_email_error = "email id already exists";
				$error = 'true';
			}
		}
	}
	//End here exist email

	if ($error == 'false'){
		if ($_POST["submit"] == 'Add')
		{
			$ins_query = "INSERT INTO `user_master` (`usr_name`,`usr_email`,`usr_password`,`usr_role`,`usr_block`,`status`) VALUES ('" . $usr_name . "','" . $usr_email . "','" . md5($usr_password) . "','" . $usr_role . "','" . $usr_block . "','" . $status . "')";

			$ins_result = mysqli_query($conn, $ins_query);
			header("location:".SITE_URL_ADMIN."/user_master.php?msg=I");
		}
		elseif($_POST["submit"] == 'Edit')
		{
			$user_update = "UPDATE `user_master` SET `usr_name`='" . $usr_name . "', `usr_email`='" . $usr_email . "', `usr_role`='" . $usr_role . "', `usr_block`='" . $usr_block . "',`status`='" . $status . "' WHERE usr_id='$id'";
			$user_result = mysqli_query($conn, $user_update);

			if($usr_password != ''){
				$user_update = "UPDATE `user_master` SET `usr_password`='" . $usr_password . "' WHERE `usr_id`='".$id."' ";
				$user_result = mysqli_query($conn, $user_update);
			}
			header("location:".SITE_URL_ADMIN."/user_master.php?msg=U");
		}
	}
}
// End insert....

//Delete ....
if($id != '' && $action == 'Delete'){
	$delete_user_query  = "SELECT * FROM `user_master` WHERE `usr_id` =  $id ";
	$delete_user_result = mysqli_query($conn, $delete_user_query);
	$delete_user_row    = mysqli_num_rows($delete_user_result);

	if ($delete_user_row > 0){
		$listing_delete  = mysqli_query($conn, "DELETE  FROM  `user_master` WHERE `usr_id`= $id");
		header("location:".SITE_URL_ADMIN."/user_master.php?msg=D");
	}
}
//End delete.........................
?>
	<tr><td width="100%">
		<table width="100%" border="1">
			<tr><td width="100%" align="center"><h3>All users</h3></td></tr>

<?php if ($action == 'Add' || $action == 'Edit') { ?>

	<tr><td><form method="POST" action="user_master.php?<?php echo $formaction; ?>">
		<table width="50%" border="1" cellpadding="5" align="center">
			<tr><td colspan="2" align="right"><a href="<?php echo SITE_URL_ADMIN.'/user_master.php';?>" class="btn btn-outline-success"><b>Back</b></a></td></tr> 
				
				<tr>
					<th>Name</th>
					<td><input type="text" name="usr_name" value="<?php echo $usr_name; ?>"><br><?php echo $usr_name_error; ?></td>
				</tr>
				<tr>
					<th>Email</th>
					<td><input type="email" name="usr_email" value="<?php echo $usr_email; ?>"><br><?php echo $usr_email_error; ?></td>
				</tr>
				<tr>
					<th>Password</th>
					<td><input type="password" name="usr_password"><br><?php echo $usr_password_error; ?></td>
				</tr>
				<tr>
					<th>Block</th>
					<td>
						<input type="radio" name="usr_block" value="Unblock" <?php echo " checked $usr_block == 'Unblock'"; ?>>Unblock
						<input type="radio" name="usr_block" value="Block" <?php if($usr_block == 'Block'){ echo 'checked="checked"'; } ?>>Block
					</td>
				</tr>
				<tr>
					<th>Role</th>
					<td>
						<select name="usr_role" value="<?php echo $usr_role; ?>"><br><?php echo $usr_role_error; ?>
							<option value="0">--select--</option>
							<option value="Admin" <?php if($usr_role == 'Admin'){ echo 'selected="selected"';} ?>>Admin</option>
							<option value="Seller" <?php if($usr_role == 'Seller'){ echo 'selected="selected"';} ?>>Seller</option>
							<option value="Customer" <?php if($usr_role == 'Customer'){ echo 'selected="selected"';} ?>>Customer</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>Status</th>
					<td><input type="radio" name="status" value="Active" <?php echo " checked $status == 'Active'"; ?>>Active
						<input type="radio" name="status" value="Deactive" <?php if ($status == 'Deactive') {
																		echo 'checked="checked"';
																	} ?>>Deactive
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" name="submit" value="<?php echo $action; ?>"></td>
				</tr>
		
	</table></form></td></tr>
	<?php } else { 
		
			            if($submitedmsg != ''){ echo $submitedmsg; } ?>
                <tr><td>
					<form method="get" action="user_master.php">
			           <input type="text" placeholder="Search.." name="search" value="<?php echo $search_value; ?>"><button type="submit" id="search_btn" class="btn btn-outline-warning">Search</button>
					</form>
				</td></tr>
				<tr><td align="right"><a href="user_master.php?action=Add" class="btn btn-outline-success"><b>Add+</b></a></td></tr>
				<tr><td>
					<table width="100%" border="1">
				<tr>
					<td><b>ID</b></td>
					<td><a href="user_master.php?<?php echo $search_url; ?>"><b>Name</b></td>
					<td><a href="user_master.php?<?php echo $search_url; ?>"><b>Email</b></td>
					<td><b>Role</b></td>
					<td><b>Block</b></td>
					<td><b>Status</b></td>
					<td><b>Action</b></td>
				</tr>
				<?php
				if($listing_result->num_rows > 0){
					while($listing_row = $listing_result->fetch_assoc()){ ?>
						<tr>
							<td><?php echo $listing_row['usr_id']; ?></td>
							<td><?php echo $listing_row['usr_name']; ?></td>
							<td><?php echo $listing_row['usr_email']; ?></td>
							<td><?php echo $listing_row['usr_role']; ?></td>
							<td><?php echo $listing_row['usr_block']; ?></td>
							<td><?php echo $listing_row['status']; ?></td>
							<td>
								<a class="btn btn-info" href="user_master.php?action=Edit&id=<?php echo $listing_row['usr_id']; ?>">Edit</a>&nbsp;
								<a class="btn btn-danger" onclick="return deletelist();" href="user_master.php?action=Delete&id=<?php echo $listing_row['usr_id']; ?>">Delete</a>
							</td>
						</tr>
				<?php }
				}
				?>
				</table>
		</td></tr>
		<tr><td> <?php include('pagination.php')?></td></tr>
	<?php
	}
	?>
	</table>
	</td></tr>
<?php include('footer.php'); ?>

