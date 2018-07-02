<?php require_once("includes/header.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
admin_nav();
$purposes=mysqli_query($connection,"SELECT  * FROM `purposes` where type='both'");
$partners=mysqli_query($connection,"SELECT  * FROM `partner`");
?>
<div class="share-wrap g-wrap">
	<div class="share-form g-form">
		<form method="post">
		<p>
				
			<select  type="text" name="partner_id" id="partner_id" value=""  tabindex="1" >
				<option disabled="disabled" selected="selected">Select Partner</option>';
				<?php while ($row=mysqli_fetch_assoc($partners)) { ?>
				<option value="<?php echo $row['partner_id']?>" ><?php echo $row['name']?></option>
				<?php } ?>
			</select>
		</p>
		<p>
				
			<select  type="text" name="pps" id="pps" value=""  tabindex="1" >
				<option disabled="disabled" selected="selected">Select Purpose</option>';
				<?php while ($row=mysqli_fetch_assoc($purposes)) { ?>
				<option value="<?php echo $row['purpose']?>" ><?php echo $row['purpose']?></option>
				<?php } ?>
				
			</select>
		</p>
		<p>
			<input type="text"  name="share" id="share"  tabindex="1" value="" placeholder="Percentage">
		</p>		
		
		<p align="center">
			<input type="submit" name="submit" value="Add Share"  class="button" tabindex="1">
		</p>
	</form>	
	</div>

	<div class="share-side g-side">
		<div class="share-table g-table"></div>
		<p class="show-more more-share">Show More...</p>
	</div>

</div>
	<div id="msg-cont">
		<div align="center"  id="action-msg">
		<p align="center"  id="msg"></p>
		<input type="button" value="Ok"  class="button">
		
		</div>
	</div>

	<div id="show-share-cont" class="show-g-cont">
		<div align="center"  id="share-popup" class="g-popup">
			<div class="pop-header">
				<span class="close-pop">&times;</span>
				<h2>Share Information</h2>
			</div>

			<p align="center"  class="pop-body"></p>
			
			<div align="center"  class="ut-body">
			</div>
		

		</div>

		
	</div>

<?php require_once("includes/footer.php"); ?>