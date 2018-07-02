<?php require_once("includes/header.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
	login(1,1);
?>
<?php
admin_nav();
?>
	<div class="voucher-form" style="width:70%;margin:0px auto">
	    <form action="preview_voucher.php" class="search-form" method="GET">
			<p align="center" style="color: #F00; font-size: 1.2em;">Search for Voucher</p>
			<p>
				
				<select  type="text" name="type" value="" required tabindex="1" >
					<option value="" disabled="disabled" selected="selected">Select Type</option>
					<option value="Deposit">Deposit</option>
					<option value="Cost">Cost</option>
					<option value="Temp">Temporary</option>

				</select>
			</p>


			<p>
			   <input type="text" name="q" required tabindex="1"  value="" placeholder="Voucher ID"/>

			</p>
			<p>
				<input type="submit" name="submit" class="button" tabindex="1" value="Search" />
			</p>
	  	</form>
  	</div>

<?php
	if(isset($_GET['type'])) { 
		$type=$_GET['type'];
		$id=$_GET['q'];
		if($type=='Cost'){
			$query="select * from `cost` where id='{$id}'";
		}
		else if($type=='Deposit'){
			$query="select * from `deposit` where id='{$id}'";
		}
		else{
			$query="select * from `temp` where id='{$id}'";
		}

		$temp=mysqli_query($connection,$query);
		$result=mysqli_fetch_assoc($temp);

		if(!$result){?>
			<div id="search_result">
				<?php 
				if($type!='Temp'){
					echo ucfirst($type);
				}
				?>
				Voucher ID : <b><?php echo $id?></b> Not Found.
			</div>
			<br>
			<br>
		<?php 
		}
		else{

			$name=$result['name'];
			$purpose=$result['purpose'];
			$description=$result['description'];
			
			$account_id=$result['account_id'];
			$date=$result['date'];
			$total = $result['total'];
			$paid = $result['paid'];
			$pay_method = $result['pay_method'];
			$due = $result['due'];
			

			$account_name=getaccount($account_id)['name'];

?>
	<div class="print-vchr">
		<?php
			echo get_print_header();
		?>	
	<table class="print-vchr-table">
			<caption class="caption">
			
			<?php 
			if($type!='Temp'){
				echo ucfirst($type);
			}
			?>

			Voucher ID : <?php echo $id?></caption>
		<tr>
			<td class="col-1">Date</td>
			<td align="left"><?php echo format_date($date)?></td>
		</tr>

		<tr>
			<td class="col-1">
			<?php 
				if($type=='Deposit') {
					echo "Name  ";
				} 
				else {
					echo "Receiver's Name";
				}
			?>
			

			</td>
			<td align="left"><?php echo $name?></td>
		</tr>
		<tr>
			<td class="col-1">Purpose</td>
			<td  align="left"><?php echo $purpose?></td>
		</tr>
		<tr>
			<td class="col-1">Details</td>
			<td align="left"><?php echo $description?></td>
		</tr>
		<tr class="vchr-ac" >
			<td class="col-1">Account</td>
			<td align="left"><?php echo $account_name?></td>
		</tr>
		
		<tr>
			<td class="col-1">Amount</td>
			<td class="col-3"><?php echo get_csn($total)?></td>
		</tr>
		<tr>
			<td class="col-1">In Words</td>
			<td class="col-3"><?php echo num_to_words($total)?></td>
		</tr>
		<tr>
			<td class="col-1">Paid</td>
			<td class="col-3"><?php echo get_csn($paid)?></td>
		</tr>
		<tr>
			<td class="col-1">Due</td>
			<td class="col-3"><?php echo get_csn($due)?></td>
		</tr>
	</table>
					<p id="sign">
					<br>
					<br>
						<span class="footer_note" style="margin-left:20px;">Receiver </span>
						<span style="float:right">Accountant</span>
					</p> 
					<div class="years-cpt" align="center"><b>Account Management System By YEARS Technology</b><br>www.yearstech.com</div>
		<br>
		<br>
			<p align="center">
			<?php
			if($type!='Temp') {
				?>
				<input type="button" class="button" onClick="addDue()" value="Add Due">
			<?php
			}
			?>
			<input type="button" class="button" title="Print" onClick="window.print()" value="Print">
			<input type="button" class="button" value="Edit" onclick="location.href='edit_voucher.php?type=<?php echo $type?>&id=<?php echo $id?>'">
			
			</p>
		</div>
		<?php 
		}
		?>
		
<div id="msg-cont">
		<div align="center"  id="action-msg">
		<p align="center"  id="msg"></p>
		<input type="submit" name="submit" value="Ok"  class="button">
		
		</div>
	</div>
		
	<div id="show-duepay-cont" class="show-g-cont">
		

		<div align="center"  id="duepay-popup" class="g-popup">
			<div class="pop-header">
				<span class="close-pop">&times;</span>
				<h2>Due Payment</h2>
			</div>
			<div align="center"  class="ut-body">
				<form method="post" >
					<table class="pps-popup-table" width="70%">
						<tr>
							<td>Voucher ID</td>
							<td> : </td>
							<td id="u-vid" ><?php echo $id?></td>
						</tr>
						<tr>
							<td>Voucher Type</td>
							<td> : </td>
							<td id="u-type" ><?php echo $type?></td>
						</tr>
						<tr>
							<td>Total</td>
							<td> : </td>
							<td id="u-total" ><?php echo $total?></td>
						</tr>

						<tr>
							<td>Paid</td>
							<td> : </td>
							<td id="u-paid" ><?php echo $paid?></td>
						</tr>
						<tr>
							<td>Due</td>
							<td> : </td>
							<td id="u-due" ><?php echo $due?></td>
						</tr>
					</table>

					<p>
						<input type="number" name="pay" id="u-pay" tabindex="1" value="" size="40" placeholder="Add Due">
					</p>
					<p>
						<input readonly type="date" name="u-date"  id="datepicker" class="u-date" value="" tabindex="1" required placeholder="Date">
						
					</p>		
					
					<p align="center">
						<input type="submit" name="submit" value="Update"  class="button" tabindex="1">
						<input type="button" name="cancel" value="Cancel" id="cancel"  class="button">
					</p>
				</form>
			</div>
			

		</div>

		
	</div>
		<?php
	}
		
		?>
<?php require_once("includes/footer.php"); ?>
