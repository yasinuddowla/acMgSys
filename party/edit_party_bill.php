<?php require_once("includes/header.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
	login(1,1);
?>
<?php
admin_nav();

$party_id = $purpose = $vno = $details = $paid = $type = $date = $account_id = $pay_method = '';
$total = 0;

$no_errors = 0;
$errors='';
$purposes=mysqli_query($connection,"SELECT  * FROM `purposes`");
$party=mysqli_query($connection,"SELECT  * FROM `party` ");

$accounts=mysqli_query($connection,"SELECT  * FROM `accounts` ");
if( isset($_POST['submit'] ) ) {
	
	$bill_id = $_POST['bill_id'];
	$party_id = $_POST['party_id'];
	$purpose = $_POST['purpose'];
	$vno = $_POST['vno'];
	$details =$_POST['details']; 
	$unit_price = $_POST['unit_price'];
	$qty = $_POST['qty'];
	$qty = $_POST['qty'];
	$total = $_POST['total'];
	$type = $_POST['type'];
	$date = $_POST['date'];
	$pay_method = $_POST['pay_method'];
	$account_id =$_POST['account_id'];

	$temp = mysqli_query($connection,"SELECT  * FROM `party` where party_id=$party_id" );
	
	if($date == ''){
		$errors.="Date Required.<br>";
		$no_errors++;
	}

	if(mysqli_num_rows($temp)==0){
		$errors.="Party Doesn't Exists.<br>";
		$no_errors++;
	}

	if(!$no_errors){

		$paid = $total;
		$due = 0;
		//Add voucher
		$res = mysqli_query($connection  ,"UPDATE `party_bill` SET `party_id`='{$party_id}', `voucher_id`='{$vno}', `account_id`='{$account_id}', `purpose`='{$purpose}', `type`='{$type}', `details`='{$details}',`unit_price`='{$unit_price}',`qty`='{$qty}', `total`='{$total}', `paid`='$paid', `due`='$due',`pay_method`='{$pay_method}', `date`='{$date}' where bill_id='{$bill_id}'");



		if(!$res){
			echo mysqli_error($connection);
		}

		else{
			redirect_to('preview_bills.php');
		}
		
	}
	else{
		
		echo ('There was an error!');
	}
}
?>
<?php 
if(isset($_GET['bill_id'])){

	$bill_id = $_GET['bill_id'];

	$temp = mysqli_query($connection,"SELECT * FROM party_bill WHERE bill_id='{$bill_id}'");

	$bill = mysqli_fetch_assoc($temp);

	$party_id = $bill['party_id'];
	$purpose = $bill['purpose'];
	$vno = $bill['voucher_id'];
	$details =$bill['details']; 
	$unit_price = $bill['unit_price'];
	$qty = $bill['qty'];
	$total = $bill['total'];
	$type = $bill['type'];
	$date = $bill['date'];
	$pay_method = $bill['pay_method'];
	$account_id =$bill['account_id'];

	?>
		<form id="voucher" class="voucher"  method="post">
			<fieldset id="personalInfo">
				<legend><strong>Party Bill</strong></legend>
				<hr>
			
			<input type="hidden" name="bill_id" value="<?php echo $bill_id?>">
			<p>
			
				<select  type="text" name="party_id" value="" required="required" tabindex="1" >
					<option value="<?php echo $party_id?>" selected>
					<?php echo getParty($party_id)['name']?>
					</option>

					<option value="" disabled="disabled">Select Party</option>

					<?php while ($row=mysqli_fetch_assoc($party)) { 
						if($row['party_id']==$party_id){
							continue;
						}
					?>
					<option value="<?php echo $row['party_id']?>">
						<?php echo $row['name']?>
					</option>
					<?php 
					} 
					?>
				</select>
			</p>

			<p>
			
				<input type="text" name="vno" value="<?php echo $vno;?>" required  placeholder="Voucher No" tabindex="1">
			</p>
			<p>
				<select id="type" name="type" value="" required="required" tabindex="1">
				<?php
					if($type=='Credit'){
						
						echo '<option value="Credit" selected>Credit</option>';
						echo '<option value=""  disabled="disabled">
							Transaction Type</option>';
						echo '<option value="Debit">Debit</option>';
					}
					else{
						
						echo '<option value="Debit" selected>Debit</option>';
						echo '<option value=""  disabled="disabled">
							Transaction Type</option>';
						echo '<option value="Credit">Credit</option>';
					}
				?>
				
				</select>
				
			</p>
			<p>
				
				<select  type="text" name="purpose" value="" required tabindex="1" >
					
					<option value="<?php echo $purpose?>" selected>
					<?php echo $purpose?>

					<option value=""  disabled="disabled">Select Purpose</option>
					
					</option>
					
					<?php while ($row=mysqli_fetch_assoc($purposes)) {
						if($row['purpose']==$purpose){
							continue;
						}
						?>
					<option value="<?php echo $row['purpose']?>" ><?php echo $row['purpose']?></option>
					<?php } ?>
					
				</select>
			</p>
			<p>
				
				<textarea type="text" name="details" value=""  placeholder="Details" rows="2" cols="42" tabindex="1"><?php echo $details;?></textarea>
			</p>
			<p>
				
				<select  type="text" name="account_id" value="<?php echo $account_id;?>" id="account" required tabindex="1">
				
					
					<option value="<?php echo $account_id?>" selected>
					<?php echo getaccount($account_id)['name']?>

					</option>
					<option value=""  disabled="disabled" >Select Account</option>
					
					<?php while ($row=mysqli_fetch_assoc($accounts)) {
						if($row['account_id']==$account_id){
							continue;
						}
						?>
					<option value="<?php echo $row['account_id']?>" ><?php echo $row['name']?></option>
					<?php } ?>
				</select>
			</p>

			

			<p>
				<input type="number" min="1" name="unit_price" value="<?php echo $unit_price;?>" id="unitPrice" required  placeholder="Unit Price" tabindex="1" onkeyup="setTotal()">
			</p>
			
			<p>
				<input type="number" min="1" name="qty" value="<?php echo $qty;?>"  id="qty" required  placeholder="Quantity" tabindex="1" onkeyup="setTotal()">
			</p>

			<p>
				<label>Total</label>
				<input type="number" readonly name="total" value="<?php echo $total;?>" id="totalPrice" required  placeholder="Total" tabindex="1" >
			</p>
			<p>
				<select id="type" name="pay_method" value="" required="required" tabindex="1">
				<?php
					
					echo '<option value="'.$pay_method.'" selected="selected">'.$pay_method.'</option>';
					
				?>
					<option value=""  disabled="disabled" >Select Payment Method</option>
					<option value="Cash">Cash</option>
					<option value="Card">Card</option>
					<option value="Check">Check</option>
					<option value="Pay Order">Pay Order</option>

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
<?php
}
?>
<?php require_once("includes/footer.php"); ?>
