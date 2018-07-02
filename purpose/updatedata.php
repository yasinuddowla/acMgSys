<?php require_once("../includes/functions.php"); ?>
<?php

if( isset($_POST['pps'] ) ) { 
		$r=mysqli_query($connection,"SELECT * FROM `purposes` 
									WHERE purpose='{$_POST['pps']}' and id!={$_POST['pps_id']}");
		//If account exists...
		
		if(mysqli_num_rows($r)>0){
			echo "Purpose Already Exists.";
		}
		else{
			set_utf8();
				$t = mysqli_query($connection  ,"UPDATE `purposes` SET purpose='{$_POST['pps']}' where id={$_POST['pps_id']}");
					
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
