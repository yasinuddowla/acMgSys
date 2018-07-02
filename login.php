<?php ob_start();?>
<?php require_once("includes/functions.php"); ?>

<?php
$username = "";
$msg = "";
if (isset($_POST['submit'])) {
  // Process the form
  
  // validations
  
    // Attempt Login

		$username = $_POST["username"];
		$password = md5($_POST["password"]);
		$user=mysqli_query($connection,"select * from user_table where username='$username' and password='$password' limit 1")
		
		or die(mysqli_error($connection));
		$row=mysqli_fetch_array($user);
    if ($row) {
      // Success
			// Mark user as logged in
			if($row['type']=='admin'){
				setcookie('admin',true,time()+60*60*24*7);
			}
			else if($row['type']=='account'){
				setcookie('account',true,time()+60*60*24*7);
			}
			else if($row['type']=='manager'){
				setcookie('manager',true,time()+60*60*24*7);
			}
			setcookie('username',$username,time()+60*60*24*7);
			setcookie('loggedin',$username,time()+60*60*24*7);
			redirect_to("index.php");
    } else {
      // Failure
      $msg = "Invalid Login.";
    }
  }

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Account Management System By YEARS Technology</title>
		<link rel="stylesheet" href="includes/style1.css" type="text/css" media="screen">
	</head>
	<body>
		
		<div class="login">
				

			<div class="login_area">
				<div class="err-login"><?php echo $msg;?></div>

				<form class="login_form"  action="login.php" method="post">
					<p>
					
						<input type="text" required name="username" value="<?php echo $username?>" tabindex="1" placeholder="Username" />
					</p>
					<p>
						
						<input type="password" required name="password" value="" tabindex="2" placeholder="Password">
					</p>
					<br>

					<p align="center">
						<input  class="button" type="submit" name="submit" value="Login" tabindex="3" />
					</p>
				</form>

			</div>
		</div>

</body>
</html>
