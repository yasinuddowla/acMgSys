<?php require_once("../includes/functions.php"); ?>
<?php

if( isset($_POST['name'] ) ) { 
		$r=mysqli_query($connection,"SELECT * FROM `party` 
									WHERE name='{$_POST['name']}' ");
		//If purpose exists...
		
		if(mysqli_num_rows($r)>0){
			echo "Party Already Exists.";
		}
		else{
			$admin_party = mysqli_query($connection, "SELECT * FROM party where party_id = 1001");
			if(mysqli_num_rows($admin_party)==0){
				//set auto increment
				mysqli_query($connection, "ALTER TABLE party AUTO_INCREMENT=1001"); 

				mysqli_query($connection, "INSERT INTO party values(NULL,'Admin','N/A','N/A','N/A',CURRENT_TIMESTAMP) ");
			}
				$t = mysqli_query($connection  ,"INSERT INTO `party`
									VALUES (NULL, '{$_POST['name']}', '{$_POST['phone']}', '{$_POST['email']}',  '{$_POST['address']}',CURRENT_TIMESTAMP)");

			if(!$t){
				echo mysqli_error($connection);
			}
			echo "Party Added Successfully.";
		}
		
}
else{
	echo "Invalid Access";
}

?>
