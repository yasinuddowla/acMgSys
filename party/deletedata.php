<?php require_once("../includes/functions.php"); ?>
<?php

if( isset($_POST['party_id'] ) ) { 
	
	$t = mysqli_query($connection  ,"DELETE FROM `party` where party_id={$_POST['party_id']}");
		
	if(!$t){
		echo mysqli_error($connection);
	}
	else
		echo "Party Deleted.";




}
else{
	echo "Invalid Access";
}

?>
