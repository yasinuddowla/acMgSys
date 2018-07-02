<?php require_once("../includes/functions.php"); ?>
<?php

if( isset($_POST['pps'] ) ) {

		//Check default purpose 'Bank Deposit'
		$ppss = mysqli_query($connection, "SELECT * FROM purposes where purpose = 'Bank Deposit'");
		if(mysqli_num_rows($ppss)==0){
			//set auto increment
			mysqli_query($connection, "ALTER TABLE purposes AUTO_INCREMENT=1001"); 

			mysqli_query($connection, "INSERT INTO purposes values(NULL,'Bank Deposit','Deposit',CURRENT_TIMESTAMP) ");
		} 
		set_utf8();
		$r=mysqli_query($connection,"SELECT * FROM `purposes` 
									WHERE purpose='{$_POST['pps']}' and (type='{$_POST['type']}' or type='both') ");
		
		//If purpose exists...
		
		if(mysqli_num_rows($r)>0){
			echo "Purpose Already Exists.";
		}
		else{
			$r=mysqli_query($connection,"SELECT * FROM `purposes` WHERE purpose='{$_POST['pps']}'");
			$r=mysqli_fetch_assoc($r);
			//If purpose exists with another type set type = both
			if($r){
				$t = mysqli_query($connection  ,"UPDATE `purposes`
									SET type='Both' WHERE purpose='{$_POST['pps']}' ");
			}

			//else add purpose
			else{
				$t = mysqli_query($connection  ,"INSERT INTO `purposes`
									VALUES (NULL, '{$_POST['pps']}', '{$_POST['type']}', CURRENT_TIMESTAMP)");
				
			}
			if(!$t){
				echo mysqli_error($connection);
			}
			echo "Purpose Added Successfully.";
		}
		
}
else{
	echo "Who the hell are you??";
}

?>
