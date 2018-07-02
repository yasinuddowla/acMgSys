<?php require_once("includes/header.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
admin_nav();

?>
<div class="party-wrap g-wrap">
	<div class="party-form g-form">
		<form method="post">
		<p>
			<input type="text" name="name" id="name" tabindex="1" value="" size="40" placeholder="Party Name">
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
		
		<p align="center">
			<input type="submit" name="submit" value="Add Party"  class="button" tabindex="1">
		</p>
	</form>	
	</div>

	<div class="party-side g-side">
		<div class="party-table g-table"></div>
		<p class="show-more more-party">Show More...</p>
	</div>

</div>
	<div id="msg-cont">
		<div align="center"  id="action-msg">
		<p align="center"  id="msg"></p>
		<input type="submit" name="submit" value="Ok"  class="button">
		
		</div>
	</div>

	<div id="show-party-cont" class="show-g-cont">
		

		<div align="center"  id="party-popup" class="g-popup">
			<div class="pop-header">
				<span class="close-pop">&times;</span>
				<h2>Party Information</h2>
			</div>

			<p align="center"  class="pop-body"></p>
			
			<div align="center"  class="ut-body">
				<form method="post" >
					<p>
						<input type="text" name="name" id="u-name" tabindex="1" value="" size="40" placeholder="Party Name">
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
						<input type="submit" name="submit" value="Update Party"  class="button" tabindex="1">
						<input type="button" name="cancel" value="Cancel" id="cancel"  class="button">
					</p>
				</form>	
			</div>
			<div class="pop-footer">
			<input type="button" name="update" value="Update"  onclick="updateParty()" class="button">
			<input type="button" name="delete" value="Delete" id="delete"  class="button" onclick="deleteParty()">
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