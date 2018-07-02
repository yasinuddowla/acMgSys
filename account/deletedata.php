<?php require_once("../includes/functions.php"); ?>
<?php

if( isset($_POST['ac_id'] ) ) { 
	
	$t = mysqli_query($connection  ,"DELETE FROM `accounts` where account_id={$_POST['ac_id']}");
		
	if(!$t){
		echo mysqli_error($connection);
	}
	else{
		echo "Account Deleted.";
	}




}
else{
	echo "Invalid Access";
}

?>
