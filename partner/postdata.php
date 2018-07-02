<?php require_once("../includes/functions.php"); ?>
<?php

if( isset($_POST['name'] ) ) { 

		$prt=mysqli_query($connection,"SELECT * FROM `partner` ");
		//If No Partner exists...
		
		if(mysqli_num_rows($prt)==0){
			//set auto increment
			mysqli_query($connection, "ALTER TABLE partner AUTO_INCREMENT=1001"); 
		}
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			echo 'Invalid Email.';
		}
		if(!is_numeric($_POST['balance'])){
			echo 'Opening Balance Have To Be Number';
		}
		else{
			set_utf8();
			$r=mysqli_query($connection,"SELECT * FROM `partner` WHERE name='{$_POST['name']}' ");
			//If purpose exists...
			
			if(mysqli_num_rows($r)>0){
				echo "Partner Already Exists.";
			}
			else{
					$t = mysqli_query($connection  ,"INSERT INTO `partner`
										VALUES (NULL, '{$_POST['name']}', '{$_POST['phone']}', '{$_POST['email']}',  '{$_POST['address']}',{$_POST['balance']},{$_POST['balance']},CURRENT_TIMESTAMP)");

				if(!$t){
					echo mysqli_error($connection);
				}
				echo "Partner Added Successfully.";
			}
		
		}
		
}
else{
	echo "Invalid Access";
}

?>
