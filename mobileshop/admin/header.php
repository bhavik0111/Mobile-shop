<?php
 	include 'connect.php';
 	session_start();
 	require "function.php";
 ?>
<!DOCTYPE html>
<html>
<head> 
</head>
<body>
<table border="1" width="100%">
	<tr><td align="center">
		<table width="100%" align="center" border="1">
			<tr><td width="100%" align="center"><h1>BhavikShop</h1></td></tr>
			<tr><td>
				<ul style="display: flex;">
					<li><a href="<?php echo SITE_URL_ADMIN;?>/user_master.php"><b>Users</b></a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<li><a href="<?php echo SITE_URL_ADMIN;?>/category.php"><b>Category</b></a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<li><a href="<?php echo SITE_URL_ADMIN;?>/product.php"><b>Product</b></a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<li><a href="<?php echo SITE_URL_ADMIN;?>/logout.php"><b>Logout</b></a></li>
				</ul>
			</td></tr>
		</table>
	</td></tr>