<?php 
include("connect.php");

$usr_name='';
$usr_email='';
$usr_password='';
$usr_role='';
$usr_block='';
$status='';



if(isset($_POST["submit"]))
  {
$usr_name = $_POST['usr_name'];
$usr_email = $_POST['usr_email'];
$usr_password = $_POST['usr_password'];
$usr_role = $_POST['usr_role'];
$usr_block = $_POST['usr_block'];
$status = $_POST['status'];	

	$ins_query = "INSERT INTO `user_master` (`usr_name`,`usr_email`,`usr_password`,`usr_role`,`usr_block`,`status`) VALUES ('" . $usr_name . "','" . $usr_email . "','" . $usr_password . "','" . $usr_role . "','" . $usr_block . "','" . $status . "')";
    $ins_result = mysqli_query($conn, $ins_query);

    if($ins_result){
    	echo "date inserted";
    }
    else{
    	echo "error";
    }


}
?>



<!DOCTYPE html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">	
</head>
<body>
<table align="center" cellspacing="0" cellpadding="15" border="1">
	<form method="POST" action="user_master.php">
	<tr>
		<td>header</td>
	</tr>
	<tr><td colspan="2" align="center"><h3>Users</h3></td></tr>
	<tr><th>Name</th><td><input type="text" name="usr_name"></td></tr>
	<tr><th>Email</th><td><input type="email" name="usr_email"></td></tr>
	<tr><th>Password</th><td><input type="password" name="usr_password"></td></tr>
	<tr>
		<th>Role</th>
			<td><select name="usr_role">
        	    <option value="0">--select--</option>
                <option value="1">admin</option>
                <option value="2">seller</option>
                <option value="3">customer</option></select>
		    </td>
	</tr>
	<tr><th>Block</th><td><input type="text" name="usr_block"></td></tr>
	<tr>	
		<th>Status</th>
        	<td><input type="radio" name="status" value="Active">Active
	            <input type="radio" name="status" value="Deactive" >Deactive
    	    </td>
    </tr>
	<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Submit"></td></tr>
  	<tr>
    	<td>footer</td>
  	</tr>
  	</form>
</table>
</body>
</html>