<?php require_once("../includes/functions.php"); ?>
<?php
if( isset($_POST['count'] ) ) { 
	$count = $_POST['count'];
		set_utf8();

		$purposes=mysqli_query($connection,"SELECT  * FROM `purposes` order by date desc Limit $count");
		if(mysqli_num_rows($purposes)==0){
			echo 'No Purpose Added Yet...';
		}
		else{
			?>
			<table class="all-table">
				<tr>
					<th width="10%">#</th>
					<th width="60%" align="left">Purpose</th>

					<th width="10%" align="left">Type</th>
					<th width="20%">Added</th>
				</tr>
			<?php
			$c=1;
		 	while ($row=mysqli_fetch_assoc($purposes)) {
		 		?>
				<tr class="show" data-pps_id="<?php echo $row['id']?>" data-pps="<?php echo $row['purpose']?>" data-added="<?php echo $row['date']?>"   data-type="<?php echo $row['type']?>" onclick="showPurpose(this)">
					<td><?php echo $c++?></td>
					<td><?php echo $row['purpose']?></td>
					<td><?php echo $row['type']?></td>
					<td><?php echo $row['date']?></td>
				</tr>				
			<?php
			} 
			echo '</table>';
			
		}
}
else{
	echo "Invalid Access";
}
?>
