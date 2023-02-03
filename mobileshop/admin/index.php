<?php 
   include('header.php');
  
    if(check_login_status() == false){
            echo "<p align='right'><b>You are  logged in.</b></p>";
    }
?>
<tr><td width="100%">
	<table width="100%" border="0" >
		<tr><th colspan="2" align="center"><?php if(isset($_SESSION["usr_name"])){ echo  "welcome" . $_SESSION["usr_name"];}?></th></tr>

<!--  --><tr><th colspan="2" align="center"><?php 
   // if(isset($_SESSION["usr_name"])){ echo $_SESSION["usr_name"];}?></th></tr>
	</table>
</td></tr>
<?php include('footer.php'); ?>