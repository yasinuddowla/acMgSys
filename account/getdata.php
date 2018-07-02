<?php require_once("../includes/functions.php"); ?>
<?php
if( isset($_POST['count'] ) ) { 
	$count = $_POST['count'];


		$accounts=mysqli_query($connection,"SELECT  * FROM `accounts` order by name Limit $count");
		if(mysqli_num_rows($accounts)==0){
			echo 'No Account Added Yet...';
		}
		else{
			?>
			<table class="all-table">
				<tr>
					<th width="10%">SL.</th>
					<th width="60%">Account Name</th>
					<th width="30%">Balance</th>
					
				</tr>
			<?php
			$c=1;
		 	while ($row=mysqli_fetch_assoc($accounts)) {
		 		?>
				<tr class="show" data-ac_id="<?php echo $row['account_id']?>" data-name="<?php echo $row['name']?>" data-balance="<?php echo $row['balance']?>" data-opening="<?php echo $row['opening']?>"  data-updt="<?php echo $row['last_updated']?>"  data-added="<?php echo $row['add_date']?>" data-type="<?php echo $row['type']?>" onclick="showAccount(this)">
					<td><?php echo $c++?></td>
					<td><?php echo $row['name']?></td>
					<td><?php echo $row['balance']?></td>
				</tr>				
			<?php
			} 
			echo '</table>';
			
		}
}
?>
