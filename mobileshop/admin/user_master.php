<?php
include 'connect.php';

$usr_name = '';
$usr_name_error = '';
$usr_email = '';
$usr_email_error='';
$usr_password = '';
$usr_password_error='';
$usr_role = '';
$usr_role_error='';
$usr_block = '';
$usr_block_error='';
$status = '';
$status_error = '';
$action='';
$id = '';
$formaction = '';
$submitedmsg = '';

if (isset($_GET['action'])){
    $action = $_GET['action'];
    $formaction = 'action=' . $action;
}

if (isset($_GET['id'])){
    $id = $_GET['id'];
}

// msg display.........
	if (isset($_GET["msg"]) && $_GET["msg"] == 'I') {
		$submitedmsg = 'Record Added';
	}
	if (isset($_GET["msg"]) && $_GET["msg"] == 'u') {
		$submitedmsg = 'Record Updated';
	}
	if (isset($_GET["msg"]) && $_GET["msg"] == 'd') {
		$submitedmsg = 'Record Deleted';
	}
//End msg..........

// insert in DB with validation..........................
if (isset($_POST['submit'])) { $error = 'false';
	
    $usr_name = $_POST['usr_name'];
		if ($usr_name == ''){
			$usr_name_error = 'Please enter Name';
			$error = 'true';
		}
    $usr_email = $_POST['usr_email'];
		if ($usr_email == ''){
			$usr_email_error = 'Please enter Email';
			$error = 'true';
		}
    $usr_password = $_POST['usr_password'];
		if ($usr_password == ''){
			$usr_password_error = 'Please enter Password';
			$error = 'true';
		}
    $usr_role = $_POST['usr_role'];
		if ($usr_role == ''){
			$usr_role_error = 'Please select Role';
			$error = 'true';
		}
    $usr_block = $_POST['usr_block'];
		if ($usr_block == ''){
			$usr_block_error = 'Please enter this field';
			$error = 'true';
		}
    $status = $_POST['status'];
	    if($status == ''){
			$status_error = 'Please enter status';
			$error ='true';
		}

	if ($error == 'false') 
    {
		if($_POST["submit"] == 'Add') 
        {
			$ins_query ="INSERT INTO `user_master` (`usr_name`,`usr_email`,`usr_password`,`usr_role`,`usr_block`,`status`)VALUES ('" .$usr_name . "','" . $usr_email . "','" . $usr_password . "','" . $usr_role . "','" . $usr_block ."','" .$status ."')";
			$ins_result = mysqli_query($conn, $ins_query);

				header("location:user_master.php?msg=I");
				//header("refresh:2");
		}
		elseif ($_POST["submit"] == 'Edit'){
            $user_update = "UPDATE `user_master` SET `usr_name`='".$usr_name."', `usr_email`='" . $usr_email . "', `usr_password`='" . $usr_password . "', `usr_role`='" . $usr_role . "', `usr_block`='" . $usr_block ."',`status`='" .$status ."' WHERE usr_id='$id'";
            $user_result = mysqli_query($conn, $user_update);

				header("location:user_master.php?msg=u");
			}
	}
}
// End insert.............................

// All query's.....................
	$listing_sql = "SELECT * FROM `user_master` WHERE `usr_id` = usr_id " ;
	$listing_result = mysqli_query($conn, $listing_sql);
// End query's.....................

// start edit form........
if ($id != '') {

    $formaction .= '&id=' . $id;
    // get edit records in form of selected id
    $update_query = "SELECT * FROM `user_master` WHERE `usr_id` =  $id ";
    $update_result = mysqli_query($conn, $update_query);

    $user_row = $update_result->fetch_assoc();
    $usr_name = $user_row['usr_name'];
    $usr_email = $user_row['usr_email'];
    $usr_password = $user_row['usr_password'];
    $usr_block = $user_row['usr_block'];
    $usr_role = $user_row['usr_role'];
	$status = $user_row['status'];
}
// End edit form..........

//Delete ................................
/*
if ($id != '' && $action == 'Delete') {

    $delete_user_query  = "SELECT * FROM `user_master` WHERE `usr_id` =  $id ";
	echo $delete_user_query;
	exit;
    $delete_user_result = mysqli_query($conn, $delete_user_query);
    $delete_user_row    = mysqli_num_rows($delete_user_result);

	if ($delete_user_row > 0) {
        $listing_delete  = mysqli_query($conn, "DELETE  FROM  `user_master` WHERE `usr_id`=" . $id);
        header("location:user_master.php?msg=Delete");
    }
}
*/
//End delete.........................

?>
<!DOCTYPE html>
<html>
	<head>
		<link href="./style.css"  rel="stylesheet">	
	</head>
	<body class="d-flex flex-column h-100">
		<?php 
			include('header.php');
		
if($action == 'Add' || $action == 'Edit')
	{ 
		?>
		<div class="user_master.php" align="right">
			<a href="user_master.php" class="btn btn-outline-success">Back</a>
		</div>
		<div class="container-fluid dash_cus mt-5 pt-5">
			<div class="header text-center">
				<h2>Add User</h2>
			</div>
			<div class="container">
				<form method="POST" action="user_master.php?<?php echo $formaction; ?>">
					<div class="row card p-5">
						<div class="col-sm-12">
							<div class="mb-3">
							<label for="Image" class="form-label">Name</label>
							<input class="form-control" type="text" id="name" name="usr_name" value="<?php echo $usr_name; ?>"><?php echo $usr_name_error ; ?>
 							</div>
						</div>
						<div class="col-sm-12">
							<div class="mb-3">
							<label for="Image" class="form-label">Email</label>
							<input class="form-control" type="email" id="email" name="usr_email" value="<?php echo $usr_email; ?>"><?php echo $usr_email_error ; ?>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="mb-3">
							<label for="Image" class="form-label">Password</label>
							<input class="form-control" type="password" name="usr_password" id="usr_password" value="<?php echo $usr_password; ?>"><?php echo $usr_password_error ; ?>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="mb-3">
							<label for="Image" class="form-label">Block</label>
							<input class="form-control" type="text" name="usr_block" id="" value="<?php echo $usr_block; ?>"><?php echo $usr_block_error ; ?>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="mb-3">
							<label for="Role" class="form-label">Role</label>
							<select name="usr_role" class="form-control" value="<?php echo $usr_role; ?>"><?php echo $usr_role_error ; ?>
							<option value="0">--select--</option>
							<option value="Admin"<?php if ($usr_role == 'Admin') { echo 'selected="selected"'; } ?>>Admin</option>
							<option value="Seller"<?php if ($usr_role == 'Seller') { echo 'selected="selected"'; } ?>>Seller</option>
							<option value="Customer"<?php if ($usr_role == 'Customer') { echo 'selected="selected"'; } ?>>Customer</option></select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="mb-3">
							<label for="Role" class="form-label">Status</label>
							<br>
								<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="1" <?php if ($status == '1') { echo 'checked="checked"'; }?>>
								<label class="form-check-label" for="inlineRadio1">Active</label>
								</div>
								<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="0" <?php if ($status == '0') { echo 'checked="checked"'; }?>>
								<label class="form-check-label" for="inlineRadio2">Deactive</label>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="mb-3" align="center">
								<input type="submit" class="btn btn-outline-success" name="submit" value="<?php echo $action; ?>">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
  <?php 
	} 
	else{ 
  ?>

		<head>
		<?php if ($submitedmsg != ''){ echo '<div class="container" align="left">' . $submitedmsg . '</div>'; }?>

			<div class="container" align="right">
				<a href="user_master.php?action=Add" class="btn btn-outline-success">Add+</a>
			</div>
			<div class="container" align="left">
				<h2 class="pb-xxl-4">All users</h2>
			</div>
		</head>
		<body>
            <table class="table">
                <thead>
                    <th>usr_id</th>
                    <th>usr_name</th>
                    <th>usr_email</th>
                    <th>usr_password</th>
                    <th>usr_role</th>
                    <th>usr_block</th>
                    <th>status</th>
                    <th>action</th>
                </thead>
                <?php
                if ($listing_result->num_rows > 0){
                    while ($listing_row = $listing_result->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo $listing_row['usr_id']; ?></td>
                            <td><?php echo $listing_row['usr_name']; ?></td>
                            <td><?php echo $listing_row['usr_email']; ?></td>
                            <td><?php echo $listing_row['usr_password']; ?></td>
                            <td><?php echo $listing_row['usr_role']; ?></td>
                            <td><?php echo $listing_row['usr_block']; ?></td>
                            <td><?php echo $listing_row['status']; ?></td>
                            <td>
                                <a class="btn btn-info" href="user_master.php?action=Edit&id=<?php echo $listing_row['usr_id']; ?>">Edit</a>&nbsp;
                                <a class="btn btn-danger"  value="Delete" href="user_master.php?action=Delete&id=<?php echo $listing_row['usr_id']; ?>">Delete</a>
                            </td>
                        </tr>
                <?php }
                }
                ?>
        </body>
	<?php
	}
  ?>
		<?php 
			// include('footer.php');
		?>
	</body>
</html>
