<?php require_once("../includes/functions.php"); ?>
<?php

if( isset($_POST['name'] ) ) { 
		$r=mysqli_query($connection,"SELECT * FROM `accounts` 
									WHERE name='{$_POST['name']}' and account_id!={$_POST['ac_id']}");
		//If account exists...
		
		if(mysqli_num_rows($r)>0){
			echo "Account Already Exists.";
		}
		else{
			set_utf8();
				$t = mysqli_query($connection  ,"UPDATE `accounts` SET name='{$_POST['name']}' where account_id={$_POST['ac_id']}");
					
				if(!$t){
					echo mysqli_error($connection);
				}
				else
					echo "Updated Successfully.";
		}
		
}
else{
	echo "Invalid Access";
}

?>
