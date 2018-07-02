<?php require_once("includes/header.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
admin_nav();
//<input type="button" name="delete" value="Delete" id="delete"  class="button" onclick="deleteAccount()">
?>
<div class="ac-wrap g-wrap">
	<div class="ac-form g-form">
		<form method="post">
		<p>
			<input type="text" name="name" id="name" tabindex="1" value="" size="40" placeholder="Account Name">
		</p>
		<p>
			<input type="number" min="1" name="name" id="opening" tabindex="1" value="" size="40" placeholder="Opening Balance">
		</p>		
		<p>
			<select id="type" name="type" value="" tabindex="1">
			<option disabled="disabled" selected="selected">Select Account Type</option>
				<option value="Bank">Bank Account</option>
				<option value="Official">Official Account</option>
				
			</select>
			
		</p>
		<p>
				<input readonly name="add_date" id="datepicker" value="" tabindex="1" required placeholder="Date">
				
		</p>
		<p align="center">
			<input type="submit" name="submit" value="Add Account"  class="button" tabindex="1">
		</p>
	</form>	
	</div>

	<div class="ac-side g-side">
		<div class="ac-table g-table"></div>
		<p class="show-more more-ac">Show More...</p>
	</div>

</div>
	<div id="msg-cont">
		<div align="center"  id="action-msg">
		<p align="center"  id="msg"></p>
		<input type="submit" name="submit" value="Ok"  class="button">
		
		</div>
	</div>

	<div id="show-ac-cont" class="show-g-cont">
		

		<div align="center"  id="ac-popup" class="g-popup">
			<div class="pop-header">
				<span class="close-pop">&times;</span>
				<h2>Account Information</h2>
			</div>

			<p align="center"  class="pop-body"></p>
			
			<div align="center"  class="ut-body">
				<form method="post" >
					<p>
						<input type="text" name="name" id="u-name" tabindex="1" value="" size="40" placeholder="Account Name">
					</p>
					<!--<p>
						<select id="utype" name="type" value="" tabindex="1">
						<option disabled="disabled" selected="selected">Select Account Type</option>
							<option value="Bank">Bank Account</option>
							<option value="Official">Official Account</option>
							
						</select>
						
					</p>	-->				
					<p align="center">
						<input type="submit" name="submit" value="Update Account"  class="button" tabindex="1">
						<input type="button" name="cancel" value="Cancel" id="cancel"  class="button">
					</p>
				</form>	
			</div>
			<div class="pop-footer">
			<input type="button" name="update" value="Update"  onclick="updateAccount()" class="button">
			
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