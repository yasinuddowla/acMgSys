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
	// if($paid > $total){
	// 	$errors .= "Paid Amount is Greater Than Total Amount.<br>";
	// 	$no_errors++;
	// }
	if(!$no_errors){

		//No errors yet.
		$stop = 0;

		
		$paid = $total;
		$due = 0;


		


		
			//Add voucher
			$res = mysqli_query($connection  ,"INSERT INTO `party_bill` (`bill_id`, `party_id`, `voucher_id`, `account_id`, `purpose`, `type`, `details`,`unit_price`,`qty`, `total`, `paid`, `due`,`pay_method`, `date`, `added`) VALUES (NULL, '{$party_id}', '{$vno}', '{$account_id}', '{$purpose}', '{$type}', '{$details}', '{$unit_price}','{$qty}','{$total}', '$paid', '$due','{$pay_method}', '{$date}', CURRENT_TIMESTAMP);");


			$sql = "SELECT `AUTO_INCREMENT` as id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '{$db_name}' AND TABLE_NAME = 'party_bill' ";
			$t=mysqli_query($connection,$sql);
			$r=mysqli_fetch_assoc($t);


			$id=$r['id']-1;

			//Add to Cost Payment Table

			$er = mysqli_query($connection  ,"INSERT INTO `party_due` (`party_due_id`, `party_id`, `pay_method`, `pre_due`, `paid`, `current_due`, `date`, `added`) VALUES (NULL, '{$party_id}', '$pay_method', '$total', '$paid', '$due', '$date', CURRENT_TIMESTAMP);");
			if(!$er){
					mysqli_error($connection);
			}

			else{
				//redirect_to('preview_bill.php?type='.$type.'&q='.$id);
			}
		
	}
	else{
		
		echo ('There was an error!');
	}
}
?>
		<form id="voucher" class="voucher"  method="post">
			<fieldset id="personalInfo">
				<legend><strong>Party Bill</strong></legend>
				<hr>

			<p id="date" align="right"></p>
			<p>
			
				<select  type="text" name="party_id" value="" required="required" tabindex="1" >
					<option value="" disabled="disabled"  selected="selected">Select Party</option>
					<?php while ($row=mysqli_fetch_assoc($party)) { ?>
					<option value="<?php echo $row['party_id']?>"><?php echo $row['name']?></option>
					<?php } ?>
				</select>
			</p>

			<p>
			
				<input type="text" name="vno" value="<?php echo $vno;?>" required  placeholder="Voucher No" tabindex="1">
			</p>
			<p>
				<select id="type" name="type" value="" required="required" tabindex="1">
				<?php
					if($type==''){
						echo '<option value=""  disabled="disabled" selected="selected">Transaction Type</option>';
					}
					else{
						echo '<option value="'.$type.'" selected="selected">'.$type.'</option>';
					}
				?>
				
					<option value="Debit">Debit</option>
					<option value="Credit">Credit</option>
					

				</select>
				
			</p>
			<p>
				
				<select  type="text" name="purpose" value="" required tabindex="1" >
					<?php
					if($purpose==''){
						echo '<option value=""  disabled="disabled" selected="selected">Select Purpose</option>';
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
				
				<textarea type="text" name="details" value=""  placeholder="Details" rows="2" cols="42" tabindex="1"><?php echo $details;?></textarea>
			</p>
			<p>
				
				<select  type="text" name="account_id" value="<?php echo $account_id;?>" id="account" required tabindex="1" >
				<?php
					if($account_id==''){
						echo '<option value=""  disabled="disabled" selected="selected">Select an Account</option>';
					}
					else{
						echo '<option value="'.$account_id.'" data-amount="'.getaccount($account_id)['balance'].'" selected="selected">'.getaccount($account_id)['name'].'</option>';
					}
				?>
					
					<?php while ($row=mysqli_fetch_assoc($accounts)) { ?>
					<option value="<?php echo $row['account_id']?>" data-amount="<?php echo $row['balance']?>"><?php echo $row['name']?></option>
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
					if($pay_method==''){
						echo '<option value=""  disabled="disabled" selected="selected">Payment Method</option>';
					}
					else{
						echo '<option value="'.$pay_method.'" selected="selected">'.$pay_method.'</option>';
					}
				?>
				
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
<?php require_once("includes/footer.php"); ?>
