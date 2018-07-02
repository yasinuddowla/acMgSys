<?php require_once("../includes/functions.php"); ?>
<?php

if( isset($_POST['partner_id'] ) ) { 
	
	$t = mysqli_query($connection  ,"DELETE FROM `partner` where partner_id={$_POST['partner_id']}");
		
	if(!$t){
		echo mysqli_error($connection);
	}
	else
		echo "Partner Deleted.";




}
else{
	echo "Invalid Access";
}

?>
