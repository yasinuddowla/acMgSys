<?php require_once("../includes/functions.php"); ?>
<?php
if( isset($_POST['count'] ) ) { 
	$count = $_POST['count'];
		

		$accounts=mysqli_query($connection,"SELECT  * FROM `share` order by id Limit $count");
		if(mysqli_num_rows($accounts)==0){
			echo 'No Share Added Yet...';
		}
		else{
			?>
			<table class="all-table">
				<tr>
					<th width="10%">SL.</th>
					<th width="40%">Partner Name</th>
					<th width="15%">Purpose</th>
					<th width="35%">Share</th>
					
				</tr>
			<?php
			$c=1;
		 	while ($row=mysqli_fetch_assoc($accounts)) {
		 		$partner_name = getPartner($row['partner_id'])['name'];
		 		$purpose = $row['purpose'];
		 		?>
				<tr class="show" data-share_id="<?php echo $row['id']?>" data-partner_name="<?php echo $partner_name?>" data-pps="<?php echo $purpose?>" data-share="<?php echo $row['share']?>"   data-added="<?php echo $row['date']?>" onclick="showShare(this)">
					<td><?php echo $c++?></td>
					<td><?php echo $partner_name?></td>
					<td><?php echo $purpose?></td>
					<td><?php echo $row['share']?>%</td>
					
				</tr>				
			<?php
			} 
			echo '</table>';
			
		}
}
?>
