<?php require_once("../includes/functions.php"); ?>
<?php

if( isset($_POST['pps_id'] ) ) { 
	
	$t = mysqli_query($connection  ,"DELETE FROM `purposes` where id={$_POST['pps_id']}");
		
	if(!$t){
		echo mysqli_error($connection);
	}
	else{
		echo "Purpose Deleted.";
	}




}
else{
	echo "Invalid Access";
}

?>
