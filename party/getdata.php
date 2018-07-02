<?php require_once("../includes/functions.php"); ?>
<?php
if( isset($_POST['count'] ) ) { 
	$count = $_POST['count'];


		$party=mysqli_query($connection,"SELECT  * FROM `party` order by name Limit $count");
		if(mysqli_num_rows($party)==0){
			echo 'No Party Added Yet...';
		}
		else{
			?>
			<table class="all-table">
				<tr>
					<th width="10%">SL.</th>
					<th width="15%">Party ID</th>
					<th width="40%">Party Name</th>
					<th width="35%">Phone No</th>
					
				</tr>
			<?php
			$c=1;
		 	while ($row=mysqli_fetch_assoc($party)) {
		 		?>
				<tr class="show" data-name="<?php echo $row['name']?>" data-email="<?php echo $row['email']?>" data-party_id="<?php echo $row['party_id']?>" data-phone="<?php echo $row['phone']?>"  data-address="<?php echo $row['address']?>"  data-added="<?php echo $row['date']?>" 
				<?php
				if($row['name']!='Admin'){
					echo 'onclick="showParty(this)"';
				}
				?>
				>
					<td><?php echo $c++?></td>
					<td><?php echo $row['party_id']?></td>
					<td><?php echo $row['name']?></td>
					<td><?php echo $row['phone']?></td>
				</tr>				
			<?php
			} 
			echo '</table>';
			
		}
}
?>
