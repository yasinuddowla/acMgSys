<?php require_once("includes/header.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
	if(!isset($_COOKIE['admin'])&&!isset($_COOKIE['account'])){
		redirect_to('login.php');
	}
?>

<?php
admin_nav();

$accounts1=mysqli_query($connection,"SELECT  * FROM `accounts` order by name");
$accounts2=mysqli_query($connection,"SELECT  * FROM `accounts`  order by name");
if( isset($_POST['transfer'] )  ) {
	
	if( $_POST['date']==''  ) {
		echo '<script>alert("Enter date");</script>';
	}
	else{
	
		$temp=mysqli_query($connection,"SELECT  * FROM `accounts` where account_id='{$_POST['from']}' order by name" );
		$temp=mysqli_fetch_assoc($temp);
		$total=$temp['balance'];
		$balance=$total-$_POST['amount'];
		$date=$_POST['date'];
		mysqli_query($connection  ,"UPDATE accounts set balance=$balance,last_updated=CURRENT_TIMESTAMP  where account_id='{$_POST['from']}'");
		$temp=mysqli_query($connection,"SELECT  * FROM `accounts` where account_id='{$_POST['to']}' order by name" );
		$temp=mysqli_fetch_assoc($temp);
		$total=$temp['balance'];
		$balance=$total+$_POST['amount'];



		$updt = mysqli_query($connection  ,"UPDATE accounts set balance=$balance,last_updated=CURRENT_TIMESTAMP  where account_id='{$_POST['to']}'");

	

		$rt = mysqli_query($connection  ,"INSERT INTO 
									`transfer` (`id`, `take_from`, `give_to`,  `amount`, `date`) 
									VALUES (NULL, '{$_POST['from']}', '{$_POST['to']}',
										'{$_POST['amount']}', '{$date}')");
		if(!$rt || !$updt){
			mysqli_error($connection);
		}
		else{
		?>
			<script src="includes/jquery.js" type="text/javascript">

			
		
			</script>
		<?php 
		}
	}
}
?>
		<form id="voucher"  class="voucher"  method="post" action="transfer.php">
			<fieldset id="personalInfo">
				<legend><strong>Transfer Slip</strong></legend>
				<hr>
			
				<p>
					<div class="label">Transfer From  <span style="color: #F00; font-size: 1em;">*</span></div>
					<select  type="text" name="from"  id="account" required tabindex="1"  onchange="getAmount(this)">
						<option value="" disabled="disabled" selected="selected">Select an Account</option>
						<?php while ($row=mysqli_fetch_assoc($accounts1)) { ?>
						<option value="<?php echo $row['account_id']?>" data-amount="<?php echo $row['balance']?>"><?php echo $row['name']?></option>
						<?php } ?>
					</select>
				</p>
				<?php
				
				?>
				<p>
					<div class="label">Transfer To  <span style="color: #F00; font-size: 1em;">*</span></div>
					<select  type="text" name="to" value="" required tabindex="1" >
						<option value="" disabled="disabled" selected="selected">Select an Account</option>
						<?php while ($row=mysqli_fetch_assoc($accounts2)) { ?>
						<<option value="<?php echo $row['account_id']?>" data-amount="<?php echo $row['balance']?>"><?php echo $row['name']?></option>
						<?php } ?>
					</select>
				</p>
			<p>
				<div class="label">Amount (in Tk.) <span style="color: #F00; font-size: 1em;">*</span></div>
				
				<input type="number" min="1" name="amount" value="" id="max_amount" required  placeholder="Total Amount" tabindex="1">
			
				
			</p>
		<p>
		   <div class="label">Date <span class="star">*</span></div>
		  <input readonly type="date" name="date" id="datepicker" value="" tabindex="1" required placeholder="Date">
		</p>
			<p align="center">
				<input type="submit" name="transfer" class="button"  tabindex="6"  value="Save" />
				<input type="reset" class="button"  value="Reset" tabindex="7"/>
			</p>

			</fieldset>
		</form>
<?php require_once("includes/footer.php"); ?>
