<?php require_once("../includes/functions.php"); ?>
<?php

if( isset($_POST['name'] ) ) { 
		
		//if no party added
		$admin_party = mysqli_query($connection, "SELECT * FROM party where party_id = 1001");
			if(mysqli_num_rows($admin_party)==0){
				//set auto increment
				mysqli_query($connection, "ALTER TABLE party AUTO_INCREMENT=1001"); 

				mysqli_query($connection, "INSERT INTO party values(NULL,'Admin','N/A','N/A','N/A',CURRENT_TIMESTAMP) ");
			}
		$dps=mysqli_query($connection,"SELECT * FROM `deposit` ");
		//If No Deposit exists...
		
		if(mysqli_num_rows($dps)==0){
			//set auto increment
			mysqli_query($connection, "ALTER TABLE deposit AUTO_INCREMENT=1001"); 
		}

		$cst=mysqli_query($connection,"SELECT * FROM `cost` ");
		//If No Cost exists...
		
		if(mysqli_num_rows($cst)==0){
			//set auto increment
			mysqli_query($connection, "ALTER TABLE cost AUTO_INCREMENT=1001"); 
		}


		$ac=mysqli_query($connection,"SELECT * FROM `accounts` ");
		//If No account exists...
		
		if(mysqli_num_rows($ac)==0){
			//set auto increment
			mysqli_query($connection, "ALTER TABLE accounts AUTO_INCREMENT=1001"); 
		}

		//Set cost payment start value...
		
		$cst=mysqli_query($connection,"SELECT * FROM `cost_payment` ");
		
		if(mysqli_num_rows($cst)==0){
			//set auto increment
			mysqli_query($connection, "ALTER TABLE cost_payment AUTO_INCREMENT=1001"); 
		}
		$dpst=mysqli_query($connection,"SELECT * FROM `deposit_payment` ");
		
		if(mysqli_num_rows($dpst)==0){
			//set auto increment
			mysqli_query($connection, "ALTER TABLE deposit_payment AUTO_INCREMENT=1001"); 
		}


		$r=mysqli_query($connection,"SELECT * FROM `accounts` 
									WHERE name='{$_POST['name']}' ");
		//If Account exists...
		
		if(mysqli_num_rows($r)>0){
			echo "Account Already Exists.";
		}
		else{



			$t1 = mysqli_query($connection  ,"INSERT INTO `accounts`
								VALUES (NULL, '{$_POST['name']}', '{$_POST['opening']}', '{$_POST['opening']}',  '{$_POST['add_date']}' ,  CURRENT_TIMESTAMP,'{$_POST['type']}')");
			
			if(!$t1){
				echo mysqli_error($connection);
			}

			$sql = "SELECT `AUTO_INCREMENT` as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '{$db_name}' AND TABLE_NAME = 'accounts' ";
			$t=mysqli_query($connection,$sql);
			$r=mysqli_fetch_assoc($t);
			$id=$r['id']-1;



			$t2 = mysqli_query($connection  ,"INSERT INTO `deposit`
								VALUES (NULL, 1001,'Bank Deposit', 'Admin',
								'Added to Bank', '{$_POST['opening']}', '{$_POST['opening']}', 0,
								'Cash', '{$_POST['add_date']}', {$id})");

			if(!$t1 || !$t2){
				echo mysqli_error($connection);
			}
			else{
				echo "Account Added Successfully.";	
			}
			
		}
		
}
else{
	echo "Invalid Access";
}

?>
