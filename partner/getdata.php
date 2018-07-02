<?php require_once("../includes/functions.php"); ?>
<?php
if( isset($_POST['count'] ) ) { 
	$count = $_POST['count'];
		set_utf8();

		$accounts=mysqli_query($connection,"SELECT  * FROM `partner` order by partner_id Limit $count");
		if(mysqli_num_rows($accounts)==0){
			echo 'No Partner Added Yet...';
		}
		else{
			?>
			<table class="all-table">
				<tr>
					<th width="10%">SL.</th>
					
					<th width="40%">Partner Name</th>
					<th width="25%">Balance</th>
					<th width="25%">Phone</th>

					
				</tr>
			<?php
			$c=1;
		 	while ($row=mysqli_fetch_assoc($accounts)) {
		 		?>
				<tr class="show" data-name="<?php echo $row['name']?>" data-email="<?php echo $row['email']?>" data-partner_id="<?php echo $row['partner_id']?>" data-phone="<?php echo $row['phone']?>"  data-balance="<?php echo $row['balance']?>" data-opening="<?php echo $row['opening']?>" data-address="<?php echo $row['address']?>"  data-added="<?php echo $row['date']?>" onclick="showPartner(this)">
					<td><?php echo $c++?></td>
					<td><?php echo $row['name']?></td>
					<td><?php echo $row['balance']?></td>
					<td><?php echo $row['phone']?></td>
				</tr>				
			<?php
			} 
			echo '</table>';
			
		}
}
?>
