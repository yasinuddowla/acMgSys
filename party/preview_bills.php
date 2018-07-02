<?php require_once("includes/header.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
	login(1,1);
?>

 <?php
admin_nav();
$party=mysqli_query($connection,"SELECT  * FROM `party` ");

$purposes=mysqli_query($connection,"SELECT  * FROM `purposes` where type='Both' or type='Cost'");
$accounts=mysqli_query($connection,"SELECT  * FROM `accounts` ");

 ?> 
<div class="io-form" style="width:70%;margin:0px auto">
    <form class="search-form" method="GET">
	<p align="center" style="color: #F00; font-size: 1.2em;">Show Party Transactions</p>
	

	<p>
		<div class="label">Select Party<span class="star">* </span></div>
		<select  type="text" name="party_id" value="" required tabindex="1" >
			
			<option value="all"  selected="selected">All</option>
			<?php while ($row=mysqli_fetch_assoc($party)) { ?>
			<option value="<?php echo $row['party_id']?>"><?php echo $row['name']?></option>
			<?php } ?>
		</select>
	</p>
	<p>
		<div class="label">Select an Account<span class="star">* </span></div>
		<select  type="text" name="account_id" value="" required tabindex="1" >
			<option value="all"  selected="selected">All</option>
			<?php while ($row=mysqli_fetch_assoc($accounts)) { ?>
			<option value="<?php echo $row['account_id']?>"><?php echo $row['name']?></option>
			<?php } ?>
		</select>
	</p>
	<p>
       <div class="label">From<span class="star">* </span></div>
		<input readonly type="date" name="from" id="date1" value="" tabindex="1" required placeholder="From Date">
				
	</p>
	<p>
       <div class="label">To<span class="star">* </span></div>
		<input readonly type="date" name="to" id="date2" value="" tabindex="1" required placeholder="To Date">
				
	</p>
	<p>
		<div class="label">Select Purpose <span class="star">* </span></div>
		<select  type="text" name="purpose" value="" required tabindex="5" >
			<option value="all"  selected="selected">All</option>
						<?php while ($row=mysqli_fetch_assoc($purposes)) { ?>
						<option value="<?php echo $row['purpose']?>" ><?php echo $row['purpose']?></option>
						<?php } ?>
		</select>
	</p>

	<p align="center">
		<input type="submit" name="submit" class="button"  tabindex="6"  value="Search" />
	</p>
  </form>
  </div>
<?php
	$count=1;

	if(isset($_GET['purpose'])) { 
	

		$pps=$_GET['purpose'];
		$party_id=$_GET['party_id'];
		$account_id=$_GET['account_id'];
		
		$s1=$_GET['from'];
		$s2=$_GET['to'];

		if($s1>$s2){
			$t=$s1;
			$s1=$s2;
			$s2=$t;
		}
		if($pps=='all'){
			$pps="'or''='";
			$purpose="ALL";
		}
		else{
			$purpose=$pps;
		}

		if($account_id=='all'){
			$account_id="'or''='";
			$account="ALL";
		}
		else{
			$account=getaccount($account_id)['name'];
		}

		//Party
		if($party_id=='all'){
			$party_id="'or''='";
			$party_name="ALL";
		}
		else{
			$party_name=getParty($party_id)['name'];
		}
		

		$query="SELECT * FROM `party_bill` WHERE date BETWEEN '{$s1}' AND 
		'{$s2}' AND (purpose='{$pps}') AND party_id='{$party_id}' and 
		(account_id = '{$account_id}') order by date asc";

		$bills=mysqli_query($connection,$query);

		if(!$bills){
			echo mysqli_error($connection);
		}

		$num_bills=mysqli_num_rows($bills);

		if($s1 == $s2){
			$duration = format_date($s1);
		}
		else{
			$duration = format_date($s1).'] - ['. format_date($s2);
		}

		if(!$num_bills){?>
			<div id="search_result">
				No Transaction for <b><?php echo $party_name?></b><br>on <b><?php echo '['.$duration.']'?></b><br>
				[Account: <b><?php echo ucfirst($account); ?>]</b><br>
				[Purpose: <b><?php echo ucfirst($purpose); ?>]</b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{

			//sum of all debit before this
			$query="SELECT sum(total)as debit FROM `party_bill` WHERE date < '{$s1}'  AND party_id='{$party_id}' and 
			(account_id = '{$account_id}') AND type = 'Debit' order by date asc";

			$debitAssoc=mysqli_query($connection,$query);
			$debit=mysqli_fetch_assoc($debitAssoc)['debit'];

			//sum of all credit before this
			$query="SELECT sum(total)as credit FROM `party_bill` WHERE date < '{$s1}' AND party_id='{$party_id}' and (account_id = '{$account_id}') AND type = 'Credit' order by date asc";

			$creditAssoc=mysqli_query($connection,$query);
			$credit=mysqli_fetch_assoc($creditAssoc)['credit'];


			$total = $debit - $credit;
			$total_debit=0;
			$total_credit=0;
?>
		<?php
			echo get_print_header();
		?>	

<table class="all-table full-broad report party">
	<caption class="caption">Transaction Information for 
		<b><?php echo $party_name?></b><br>
		on <b><?php echo '['.$duration.']'?></b><br>
		[Account: <b><?php echo ucfirst($account); ?>]</b><br>
		[Purpose: <b><?php echo ucfirst($purpose); ?>]</b>
	</caption>
	<tr>
		<th width="5%">*</th>
		<th width="8%">SL</th>
		<th width="12%">Voucher No</th>
		<th width="12%">Date</th>
		<th width="16%">Purpose</th>
		<th width="17%">Account</th>
		<th width="10%">Debit</th>
		<th width="10">Credit</th>
		<th width="10">Balance</th>

	</tr>
	<tr>
		<td>-</td>
		<td>-</td>
		<td>-</td>
		<td>-</td>
		<td>-</td>
		<td>Opening</td>
		<td>-</td>
		<td>-</td>
		<td><?php echo $total?></td>
	</tr>
	
	<?php
	$count =1;
	while($r = mysqli_fetch_assoc($bills)){
		
		if($r['type']=='Debit'){
			$total_debit  += $r['total'];	
			$total += $r['total'];
			echo '<tr>';
				echo '<td><button  class=" btn-primary" onclick="location.href=\'edit_party_bill.php?bill_id='.$r['bill_id'].'\'" ><i class="fa fa-edit"></i></button></td>';
				echo '<td>'.$count++.'</td>';
				echo '<td>'.$r['voucher_id'].'</td>';
				echo '<td>'.format_date($r['date']).'</td>';
				echo '<td>'.$r['purpose'].'</td>';
				echo '<td>'.getaccount($r['account_id'])['name'].'</td>';
				echo '<td>'.$r['total'].'</td>';
				echo '<td>-</td>';
				echo '<td>'.$total.'</td>';
			echo '</tr>';
		}
		else{
			$total_credit  += $r['total'];	
			$total -= $r['total'];
			echo '<tr>';
				echo '<td><button  class=" btn-primary" onclick="location.href=\'edit_party_bill.php?bill_id='.$r['bill_id'].'\'" ><i class="fa fa-edit"></i></button></td>';
				echo '<td>'.$count++.'</td>';
				echo '<td>'.$r['voucher_id'].'</td>';
				echo '<td>'.format_date($r['date']).'</td>';
				echo '<td>'.$r['purpose'].'</td>';
				echo '<td>'.getaccount($r['account_id'])['name'].'</td>';
				echo '<td>-</td>';
				echo '<td>'.$r['total'].'</td>';
				echo '<td>'.$total.'</td>';
			echo '</tr>';
		}
		
	}
	?>
	<tr >
		<td colspan="5" >-</td>
		
		<td  style="text-align:center;;"><b>Total</b></td>
	
		<td><b><?php echo get_csn($total_debit)?></b></td>
		<td><b><?php echo get_csn($total_credit)?></b></td>
		<td><b><?php echo get_csn($total)?></b></td>

		
	</tr>
	<?php
	/*
	?>
	<tr >
		
		<td colspan="1"><?php echo num_to_words($total_deposit)?></td>
		<td colspan="1"><?php echo num_to_words($total_dp_paid)?></td>
		<td colspan="1"><?php echo num_to_words($total_dp_due)?></td>
		<td colspan="1"><?php echo num_to_words($total_cost)?></td>
		<td colspan="1"><?php echo num_to_words($total_debit)?></td>
		<td colspan="1"><?php echo num_to_words($total_credit)?></td>
		
	</tr>
	<?php  */
	?>

</table>
<br>
<br>
<div class="years-cpt" align="center"><b>www.yearstech.com</b></div>
<br>


<p  align="center" style="width:80%;margin:0px auto">
			<input type="button" id="print" title="Print" onClick="window.print()" value="Print">
</p>
<br>
	<?php } 
	}

	?>
<?php require_once("includes/footer.php"); ?>
