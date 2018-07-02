<?php require_once("includes/header.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
	login(1,1);
?>

 <?php
admin_nav();
$years = mysqli_query($connection,"SELECT  distinct YEAR(date) as year FROM `cost` UNION SELECT  distinct YEAR(date) as year FROM `deposit` ");

$accounts=mysqli_query($connection,"SELECT  * FROM `accounts` ");
 ?> 
	<div class="io-form" style="width:70%;margin:0px auto">
    <form action="view_monthly_bank_transaction.php" class="search-form" method="GET">
		<p align="center" style="color: #F00; font-size: 1.2em;">Show Monthly Transactions</p>

		<p  >
			<div class="label">Select Account  <span class="star">* </span></div> 
			<select  type="text" name="account_id" value="" required tabindex="1">
				<option value="all"  selected="selected">All</option>
				<?php while ($row=mysqli_fetch_assoc($accounts)) { ?>
				<option value="<?php echo $row['account_id']?>"><?php echo $row['name']?></option>
				<?php } ?>
			</select>
		</p>

	<p align="center">
       <div class="label">Enter Year and Month<span class="star">* </span></div>
	   		<select  type="text" name="year" value="" required tabindex="1" style="width: 45%;">
				<option disabled="disabled"  selected="selected">Year</option>
				<?php while ($row=mysqli_fetch_assoc($years)) { ?>
				<option value="<?php echo $row['year']?>"><?php echo $row['year']?></option>
				<?php } ?>
			</select>
			<select  type="text" name="month" value="" required tabindex="1"  style="width: 45%;">
				<option disabled="disabled"  selected="selected">Month</option>
				<option value="01">January</option>
				<option value="02">February</option>
				<option value="03">March</option>
				<option value="04">April</option>
				<option value="05">May</option>
				<option value="06">June</option>
				<option value="07">July</option>
				<option value="08">August</option>
				<option value="09">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>


			</select>
	</p>
	<p align="center">
		<input type="submit" name="submit" class="button"  tabindex="6"  value="Search" />
	</p>
  </form>
  </div>

<?php
$costs=mysqli_query($connection,"SELECT  * FROM `cost` ");


$count=1;
?>
<?php
	if(isset($_GET['year'])) { 
	
		$year=intval($_GET['year']);
		$month=intval($_GET['month']);
		$a_id=$_GET['account_id'];
		
		if($a_id=='all' || $a_id=="'or''='"){
			$a_id="'or''='";
			$account_name='ALL Accounts';
		}
		else{
			$temp=mysqli_query($connection,"SELECT  * FROM `accounts` where account_id='{$a_id}'");
			$temp=mysqli_fetch_assoc($temp);
			$account_name=$temp['name'];
		}
		
		if($month<10){
			$month='0'.$month;
		}
		$search_time=$year.'-'.$month.'-';
		
		$query="select * from `cost` where date like '{$search_time}%' and  account_id='{$a_id}'";
		$costs=mysqli_query($connection,$query);
		$costs=mysqli_fetch_assoc($costs);
		
		$query="select * from `deposit` where date like '{$search_time}%' and account_id='{$a_id}'";
		$deposits=mysqli_query($connection,$query);
		$deposits=mysqli_fetch_assoc($deposits);
		
		if(!$costs && !$deposits){?>
			<div id="search_result">
	No Transaction for<br><b><i><?php echo $account_name.' ['. get_month_name($month).', '.$year.']'?></i></b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{
		$dt=$search_time.'01';
		
		$query="SELECT sum(total) as cost FROM `cost` where date < '{$dt}' and (account_id='{$a_id}')";
		$t1=mysqli_query($connection,$query);
		$t1=mysqli_fetch_assoc($t1);
		$query="SELECT sum(total) as deposit FROM `deposit` where date < '{$dt}' and (account_id='{$a_id}')";

		
		$t2=mysqli_query($connection,$query);
		$t2=mysqli_fetch_assoc($t2);
		
		/*finds amount transfered to another account  */
		
		$q1="SELECT sum(amount) as amount FROM `transfer` where (take_from='{$a_id}') and  date < '{$dt}' ";
		$gone=mysqli_query($connection,$q1);
		$gone=mysqli_fetch_assoc($gone);
		
		/*finds amount transfered to this account  */
		
		$q2="SELECT sum(amount) as amount FROM `transfer` where (give_to='{$a_id}') and  date < '{$dt}' ";
		$came=mysqli_query($connection,$q2);
		$came=mysqli_fetch_assoc($came);

		
		$opening=$t2['deposit'] - $t1['cost'] + $came['amount'] - $gone['amount'];
		$total_cost=0;
		$total_deposit=0;
	
?>
		<?php
			echo get_print_header();
		?>

<table class="all-table full-broad report">
	<caption class="caption">Monthly Transaction Information of <br><b><i><?php echo $account_name.' ['. get_month_name($month).', '.$year.']'?></i></b></caption>
	<tr>
		<th width="15%">Sl</th>
		<th width="25%">Purpose</th>
		<th width="20%">Deposits</th>
		<th width="20%">Costs</th>
		<th width="20%">Balance</th>
	</tr>
	<tr>
		<td>-</td>
		<td><b>Opening</b></td>
		<td>-</td>
		<td>-</td>
		<td><?php echo get_csn($opening)?></td>
	</tr>
	
		

	<?php
		
		$query="select distinct(purpose) as purpose from `deposit` where date like '{$search_time}%' and (account_id='{$a_id}') UNION select distinct(purpose) as purpose from `cost` where date like '{$search_time}%' and (account_id='{$a_id}') ";


		$purposes=mysqli_query($connection,$query);
		
		while($pr=mysqli_fetch_assoc($purposes)){
			$query="select sum(total) as total from `deposit` where date like '{$search_time}%' and (account_id='{$a_id}') and purpose='{$pr['purpose']}' ";
			
			//purpose data for deposit
			$ppsDtdep=mysqli_query($connection,$query);
			$depPps=mysqli_fetch_assoc($ppsDtdep);
			$total_deposit+=$depPps['total'];
			?>
			<tr>
				<td rowspan="2"><?php echo $count++?></td>
				<td rowspan="2"><?php echo $pr['purpose']?></td>
				<td><?php echo get_csn($depPps['total'])?></td>
				<td>-</td>
				<?php
				$opening+=$depPps['total'];
				?>
				<td><?php echo get_csn($opening)?></td>
			</tr>
			<?php
			$query="select sum(total) as total from `cost` where date like '{$search_time}%' and (account_id='{$a_id}') and purpose='{$pr['purpose']}' ";
			
			//purpose data for cost
			$ppsDtcost=mysqli_query($connection,$query);
			$costPps=mysqli_fetch_assoc($ppsDtcost);
			$total_cost+=$costPps['total'];
			?>
			<tr>
				
				
				<td>-</td>
				<td><?php echo get_csn($costPps['total'])?></td>
				
				<?php
				$opening-=$costPps['total'];
				?>
				<td><?php echo get_csn($opening)?></td>
			</tr>
		<?php
		}
			$came=mysqli_query($connection,"SELECT sum(amount) as amount FROM `transfer` where date like '{$search_time}%' and (give_to='{$a_id}') ");
			
			$came=mysqli_fetch_assoc($came);
			$total_deposit+=$came['amount'];
			?>
			<tr>
				<td rowspan="2"><?php echo $count++?></td>
				<td rowspan="2"><i>Transfered</i></td>
				<td><?php echo get_csn($came['amount'])?></td>
				<td>-</td>
				<?php
				$opening+=$came['amount'];
				?>
				<td><?php echo get_csn($opening)?></td>
			</tr>
		<?php
			$gone=mysqli_query($connection,"SELECT sum(amount) as amount FROM `transfer` where date like '{$search_time}%' and (take_from='{$a_id}') ");
			
			$gone=mysqli_fetch_assoc($gone);
			$total_cost+=$gone['amount'];
			?>
			<tr>
				
				<td>-</td>
				<td><?php echo get_csn($gone['amount'])?></td>
				
				<?php
				$opening-=$gone['amount'];
				?>
				<td><?php echo get_csn($opening)?></td>
			</tr>
	<tr>
		<td>-</td>
		<td><b>Closing</b></td>
		<td>-</td>
		<td>-</td>
		<td><?php echo get_csn($opening)?></td>
	</tr>

	<tr >
		<td colspan="2" rowspan="2" style="text-align:right;padding-right:100px"><b>Total</b></td>
		<td><?php echo get_csn($total_deposit)?></td>
		<td><?php echo get_csn($total_cost)?></td>
		<td></td>
		
	</tr>
	<tr >
		<td colspan="1"><?php echo num_to_words($total_deposit)?></td>
		<td colspan="1"><?php echo num_to_words($total_cost)?></td>
		<td><?php echo num_to_words($opening)?></td>
	</tr>

</table>
<br>
<br>
<?php

	}
	?>

<div class="years-cpt" align="center"><b>www.yearstech.com</b></div>
<br>
<br>
<p  align="center" style="width:80%;margin:0px auto">
			<input type="button" id="print" title="Print" onClick="window.print()" value="Print">
</p>

<br>
<br>
	<?php } 

	?>
<?php require_once("includes/footer.php"); ?>
