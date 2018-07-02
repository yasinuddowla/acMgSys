<?php require_once("includes/header.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
admin_nav();

$party_id = $type = $purpose = $name = $description = $total = $paid = $pay_method = $date = $account_id = '';
$no_errors = 0;
$errors='';
$purposes=mysqli_query($connection,"SELECT  * FROM `purposes`");

$accounts=mysqli_query($connection,"SELECT  * FROM `accounts` ");
if( isset($_POST['submit'] ) ) {
	$party_id = $_POST['party_id'];
	$type = $_POST['type'];
	$purpose = $_POST['purpose'];
	$name = $_POST['name'];
	$description =$_POST['description']; 
	$total = $_POST['total'];
	$paid = $_POST['paid'];
	$pay_method = $_POST['pay_method'];
	$date = $_POST['date'];
	$account_id =$_POST['account_id'];
	$temp = mysqli_query($connection,"SELECT  * FROM `party` where party_id=$party_id" );
	
	if(mysqli_num_rows($temp)==0){
		$errors.="Party Doesn't Exists.<br>";
		$no_errors++;
	}
	if($paid > $total){
		$errors .= "Paid Amount is Greater Than Total Amount.<br>";
		$no_errors++;
	}
	if(!$no_errors){
	
		$date=$_POST['date'];
		$due = $_POST['total']-$_POST['paid'];;

		$rt = mysqli_query($connection  ,"INSERT INTO `temp`
									VALUES (NULL, {$_POST['party_id']},'{$_POST['type']}','{$_POST['purpose']}', '{$_POST['name']}',
									'{$_POST['description']}', '{$_POST['total']}', '{$_POST['paid']}', {$due},'{$_POST['pay_method']}',
									'{$date}', '{$_POST['account_id']}')");

		if(!$rt){
			mysqli_error($connection);
		}
		$sql = "SELECT `AUTO_INCREMENT` as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '{$db_name}' AND TABLE_NAME = 'temp' ";
		$t=mysqli_query($connection,$sql);
		$r=mysqli_fetch_assoc($t);
		$id=$r['id']-1;
		?>
		
		<?php
		redirect_to('preview_voucher.php?type=Temp&q='.$id);
	}
	else{
		
		?>
		<script>
				window.onload=function(){
					showMsg("<?php echo $errors ?>");
			}
		</script>
	<?php
	}
}
?>
		<form id="voucher" class="voucher"  method="post" action="temp_voucher.php">
			<fieldset id="personalInfo">
				<legend><strong>Temporary Voucher</strong></legend>
				<hr>

			<p id="date" align="right"></p>
			<p>
			
				<input type="text" name="party_id" value="<?php echo $party_id;?>" required  placeholder="Party ID" tabindex="1">
			</p>

			<p>
			
				<input type="text" name="name" value="<?php echo $name;?>" required  placeholder="Reciever's Name" tabindex="1">
			</p>
			
			<p>
				<select id="type" name="type" value="" required="required" tabindex="1">
				<?php
					if($type==''){
						echo '<option disabled="disabled" selected="selected">Voucher Type</option>';
					}
					else{
						echo '<option value="'.$type.'" selected="selected">'.$type.'</option>';
					}
				?>
				
					<option value="Cost">Cost</option>
					<option value="Deposit">Deposit</option>
					

				</select>
				
			</p>

			<p>
				
				<select  type="text" name="purpose" value="" required tabindex="1" >
					<?php
					if($purpose==''){
						echo '<option disabled="disabled" selected="selected">Select Purpose</option>';
					}
					else{
						echo '<option value="'.$purpose.'" selected="selected">'.$purpose.'</option>';
					}
					?>
					
					<?php while ($row=mysqli_fetch_assoc($purposes)) { ?>
					<option value="<?php echo $row['purpose']?>" ><?php echo $row['purpose']?></option>
					<?php } ?>
					
				</select>
			</p>
			<p>
				
				<textarea type="text" name="description" value=""  placeholder="Details" rows="2" cols="42" tabindex="1"><?php echo $description;?></textarea>
			</p>
			<p>
				
				<select  type="text" name="account_id" value="<?php echo $account_id;?>" id="account" required tabindex="1"  onchange="getAmount(this)">
				<?php
					if($account_id==''){
						echo '<option disabled="disabled" selected="selected">Select an Account</option>';
					}
					else{
						echo '<option value="'.$account_id.'" data-amount="'.getaccount($account_id)['balance'].'" selected="selected">'.getaccount($account_id)['name'].'</option>';
					}
				?>
					
					<?php while ($row=mysqli_fetch_assoc($accounts)) { ?>
					<option value="<?php echo $row['id']?>" data-amount="<?php echo $row['balance']?>"><?php echo $row['name']?></option>
					<?php } ?>
				</select>
			</p>

			

			<p>
				<input type="number" min="1" name="total" value="<?php echo $total;?>" id="total" required  placeholder="Total Amount" tabindex="1">
			</p>
			<p>
				<input type="number" min="1" name="paid" value="<?php echo $paid;?>" id="total" required  placeholder="Paid Amount" tabindex="1">
			</p>
			<p>
				<select id="type" name="pay_method" value="" required="required" tabindex="1">
				<?php
					if($pay_method==''){
						echo '<option disabled="disabled" selected="selected">Payment Method</option>';
					}
					else{
						echo '<option value="'.$pay_method.'" selected="selected">'.$pay_method.'</option>';
					}
				?>
				
					<option value="Cash">Cash</option>
					<option value="Card">Card</option>
					<option value="Check">Check</option>

				</select>
				
			</p>
			<p>
				<input readonly type="date" name="date" id="datepicker" value="<?php echo $date;?>" tabindex="1" required placeholder="Date">
				
			</p>
			<p align="center">
				<input type="submit" name="submit" class="button"  tabindex="6"  value="Save" />
				<input type="reset" class="button"  value="Reset" tabindex="7"/>
			</p>

			</fieldset>
		</form>
	<div id="msg-cont">
		<div align="center"  id="action-msg">
		<p align="center"  id="msg"></p>
		<input type="submit" name="submit" value="Ok"  class="button">
		
		</div>
	</div>
<?php require_once("includes/footer.php"); ?>
