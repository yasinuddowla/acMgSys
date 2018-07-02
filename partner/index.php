<?php require_once("includes/header.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
admin_nav();

?>
<div class="partner-wrap g-wrap">
	<div class="partner-form g-form">
		<form method="post">
		<p>
			<input type="text" name="name" id="name" tabindex="1" value="" size="40" placeholder="Partner Name">
		</p>
		<p>
			<input type="text" name="phone" id="phone" tabindex="1" value="" size="40" placeholder="Phone No">
		</p>

		<p>
			<input type="text"  name="email" id="email" tabindex="1" value="" size="40" placeholder="Email">
		</p>
		<p>
			<input type="text" name="address" id="address" tabindex="1" value="" size="40" placeholder="Address">
		</p>		
		<p>
			<input type="text" name="balance" id="balance" tabindex="1" value="" size="40" placeholder="Opening Balance">
		</p>
		<p align="center">
			<input type="submit" name="submit" value="Add Partner"  class="button" tabindex="1">
		</p>
	</form>	
	</div>

	<div class="partner-side g-side">
		<div class="partner-table g-table"></div>
		<p class="show-more more-partner">Show More...</p>
	</div>

</div>
	<div id="msg-cont">
		<div align="center"  id="action-msg">
		<p align="center"  id="msg"></p>
		<input type="button" value="Ok"  class="button">
		
		</div>
	</div>

	<div id="show-partner-cont" class="show-g-cont">
		

		<div align="center"  id="partner-popup" class="g-popup">
			<div class="pop-header">
				<span class="close-pop">&times;</span>
				<h2>Partner Information</h2>
			</div>

			<p align="center"  class="pop-body"></p>
			
			<div align="center"  class="ut-body">
				<form method="post" >
					<p>
						<input type="text" name="name" id="u-name" tabindex="1" value="" size="40" placeholder="Partner Name">
					</p>
					<p>
						<input type="text" name="phone" id="u-phone" tabindex="1" value="" size="40" placeholder="Phone No">
					</p>

					<p>
						<input type="text"  name="email" id="u-email" tabindex="1" value="" size="40" placeholder="Email">
					</p>
					<p>
						<input type="text" name="address" id="u-address" tabindex="1" value="" size="40" placeholder="Address">
					</p>		
					
					<p align="center">
						<input type="submit" name="submit" value="Update Partner"  class="button" tabindex="1">
						<input type="button" name="cancel" value="Cancel" id="cancel"  class="button">
					</p>
				</form>	
			</div>
			<div class="pop-footer">
			<input type="button" name="update" value="Update"  onclick="updatePartner()" class="button">
			<input type="button" name="delete" value="Delete" id="delete"  class="button" onclick="deletePartner()">
			</div>

		</div>

		
	</div>


	<div id="confirm-cont">
		<div align="center" id="confirm-msg">
			<p align="center">Are You Sure</p>
			<input type="button" id="yes" value="Yes" class="button">
			<input type="button" id="no" value="No" class="button">
			
		</div>
	</div>
	

<?php require_once("includes/footer.php"); ?>