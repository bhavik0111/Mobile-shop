<?php 
include('login_header.php');


if(isset($_SESSION["usr_role"])){ 
   header("location:index.php");
}

if(isset($_POST['submit']))
{
    $usr_name =$_POST['usr_name'];
    $usr_password =$_POST['usr_password'];
    $usr_role = $_POST['usr_role'];

    $usr_query = "SELECT * FROM `user_master` WHERE `usr_name`='".$usr_name."' AND `usr_password`='".$usr_password."' ";
    $usr_result = mysqli_query($conn, $usr_query);

        if(mysqli_num_rows($usr_result) === 1)
        {
           $_SESSION["usr_name"] = $usr_name; 
           $_SESSION["usr_password"] = $usr_password; 
           $_SESSION["usr_role"] = $usr_role;
           ...loggedin
           
            header("location:index.php");
        }
        else{
           header("location:login.php?msg");
        }
}


        

?>
<tr><td align="center"><h3><b>User login</b></h3></td></tr>
<tr><td align="center"><?php if(isset($_GET["msg"])){ echo "Invalid username or password...!"; } ?></td></tr>
<tr><td>
    <form method="post" action="login.php">    
        <table align="center" border="1" cellspacing="0" cellpadding="15">
            <tr>
                <th colspan="2" align="center">Login hear...</th>
            </tr>
            <tr>
                <th>Username</th>
                <td><input type="text" name="usr_name" placeholder="enter name..."> 
            </tr>
            <tr>
                <th>Password</th>
                <td><input type="password" name="usr_password" placeholder="enter password..."></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" name="submit" value="Login"></td>
            </tr>
        </table>
    </form>
</td></tr>
<?php include('login_footer.php');    ?>

