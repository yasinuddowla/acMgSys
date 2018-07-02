<?php require_once("includes/header.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
admin_nav();

?>
<div class="pps-wrap g-wrap">
	<div class="pps-form g-form">
		<form method="post">
		<p>
			
			<input type="text" name="pps" id="pps" tabindex="1" value="" size="40" placeholder="Purpose Name">
		</p>
		<p>
			<select id="type" name="type" value="" tabindex="1">
			<option disabled="disabled" selected="selected">Select Purpose Type</option>
				<option value="Cost">Cost</option>
				<option value="Deposit">Deposit</option>
				<option value="Both">Both</option>

			</select>
			
		</p>
		<p align="center">
			<input type="submit" name="submit" value="Add Purpose"  class="button" tabindex="1">
		</p>
	</form>	
	</div>

	<div class="pps-side g-side">
		<div class="pps-table g-table"></div>
		<p class="show-more more-pps">Show More...</p>
	</div>

</div>
	<div id="msg-cont">
		<div align="center"  id="action-msg">
		<p align="center"  id="msg"></p>
		<input type="submit" name="submit" value="Ok"  class="button">
		
		</div>
	</div>

<div id="show-pps-cont" class="show-g-cont">
		

		<div align="center"  id="pps-popup" class="g-popup">
			<div class="pop-header">
				<span class="close-pop">&times;</span>
				<h2>Purpose Information</h2>
			</div>

			<p align="center"  class="pop-body"></p>
			
			<div align="center"  class="ut-body">
				<form method="post" >
					<p>
						<input type="text" name="pps" id="u-pps" tabindex="1" value="" size="40" placeholder="Purpose Name">
					</p>
					<!--<p>
						<select id="utype" name="type" value="" tabindex="1">
						<option disabled="disabled" selected="selected">Select Account Type</option>
							<option value="Bank">Bank Account</option>
							<option value="Official">Official Account</option>
							
						</select>
						
					</p>	-->				
					<p align="center">
						<input type="submit" name="submit" value="Update Purpose"  class="button" tabindex="1">
						<input type="button" name="cancel" value="Cancel" id="cancel"  class="button">
					</p>
				</form>	
			</div>
			<div class="pop-footer">
			<input type="button" name="update" value="Update"  onclick="updatePurpose()" class="button">
			<input type="button" name="delete" value="Delete" id="delete"  class="button" onclick="deletePurpose()">
			</div>

		</div>

		
	</div>


	<div id="confirm-cont">
		<div align="center" id="confirm-msg">
			<p align="center">Are You Sure?</p>
			<input type="button" id="yes" value="Yes" class="button">
			<input type="button" id="no" value="No" class="button">
			
		</div>
	</div>
	


<?php require_once("includes/footer.php"); ?>