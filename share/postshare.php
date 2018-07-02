<?php require_once("../includes/functions.php"); ?>
<?php

if( isset($_POST['partner_id'] ) ) { 
		if (!is_numeric($_POST['share'])) {
			echo 'Percentage <b>Must Be</b> A Number.';
		}
		else{
			
			$r=mysqli_query($connection,"SELECT * FROM `share` WHERE partner_id='{$_POST['partner_id']}' AND purpose='{$_POST['pps']}' ");
			//If purpose exists...
			
			if(mysqli_num_rows($r)>0){
				echo "Share Already Exists.";
			}
			else{

				//check if share is over 100%
				$r=mysqli_query($connection,"SELECT sum(share) as sum_share FROM `share` WHERE purpose='{$_POST['pps']}' ");
				$row = mysqli_fetch_assoc($r);
				$sum_share = $row['sum_share'];

				if($_POST['share'] + $sum_share >100.00){
					if($sum_share==100.00){
						echo 'Already Shared 100%.';
					}
					else{
						echo 'Maximum Possible Share is '.(100.00-$sum_share).'%.';
					}
				}
				else{
						$t = mysqli_query($connection  ,"INSERT INTO `share`
											VALUES (NULL, '{$_POST['partner_id']}', '{$_POST['pps']}', '{$_POST['share']}', CURRENT_TIMESTAMP)");

					if(!$t){
						echo mysqli_error($connection);
					}
					echo "Share Added Successfully.";
				}
			}
		
		}
		
}
else{
	echo "Invalid Access";
}

?>
