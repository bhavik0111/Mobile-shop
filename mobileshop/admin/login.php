<?php 
include('header.php');


if(isset($_POST['usr_name']) && isset($_POST['usr_password'])){

    $usr_name =$_POST['usr_name'];
    $usr_password =$_POST['usr_password'];

        if($usr_name == '' || $usr_password == ''){
            header("Location:login.php");
            exit();
        }
        else{
            $sql = "SELECT * FROM `user_master` WHERE `usr_name`='".$usr_name."' AND `usr_password`='".$usr_password."' ";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) === 1){
                $row = mysqli_fetch_assoc($result);

                if ($row['usr_name'] === $usr_name && $row['usr_password'] === $usr_password){
                    echo "Logged in!";
                    
                    header("Location: user_master.php");
                    exit();
                }
            }
        }
}else{
    header("Location: login.php");
    exit();
}


 


    

?>
<!doctype html>
<html>
  <head>
    <title>Login</title>
  </head>
  <body>
    <table align="center" border="1" cellspacing="0" cellpadding="15">
        <tr>
            <th colspan="2" align="center">Login</th>
        </tr>
        <tr>
            <th>Username</th>
            <td><input type="text" name="username" placeholder="enter name..."> 
        </tr>
        <tr>
            <th>Password</th>
            <td><input type="password" name="password" placeholder="enter password..."></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" name="Login">
               <a href="signup.php" class="btn btn-outline-info">Signup</a>
            </td>
        </tr>
    </table>
  </body>
</html>
