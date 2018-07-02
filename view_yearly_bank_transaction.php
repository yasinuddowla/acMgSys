<?php require_once("includes/header.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
	login(1,1);
?>

 <?php
admin_nav();
$years = mysqli_query($connection,"SELECT  distinct YEAR(date) as year FROM `cost` UNION SELECT  distinct YEAR(date) as year FROM `deposit` ");

$purposes=mysqli_query($connection,"SELECT  * FROM `purposes` ");

$accounts=mysqli_query($connection,"SELECT  * FROM `accounts` ");
 ?> 
	<br>
<div class="io-form" style="width:70%;margin:0px auto">
    <form action="view_yearly_bank_transaction.php" class="search-form" method="GET">
		<p align="center" style="color: #F00; font-size: 1.2em;">Show Yearly Transactions</p>

		<p>
			<div class="label">Select Account  <span class="star">* </span></div>
			<select  type="text" name="account_id" value="" required tabindex="1" >
				<option value="all"  selected="selected">All</option>
				<?php while ($row=mysqli_fetch_assoc($accounts)) { ?>
				<option value="<?php echo $row['id']?>"><?php echo $row['name']?></option>
				<?php } ?>
			</select>
	</p>
	<p>
		<div class="label">Select Purpose  <span class="star">*</span></div>
		<select  type="text" name="purpose" value="" required tabindex="1"  >
			<option value="all"  selected="selected">All</option>
						<?php while ($row=mysqli_fetch_assoc($purposes)) { ?>
						<option value="<?php echo $row['purpose']?>" ><?php echo $row['purpose']?></option>
						<?php } ?>
		</select>
	</p>

	<p >
       <div class="label">Enter Opening Year <span class="star">* </span></div>
	   <select  type="text" name="year" value="" required tabindex="1" >
				<option disabled="disabled"  selected="selected">Year</option>
				<?php while ($row=mysqli_fetch_assoc($years)) { ?>
				<option value="<?php echo $row['year']?>"><?php echo $row['year']?></option>
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
?>
<?php
	if(isset($_GET['submit'])) { 
	
		$year=$_GET['year'];
		$a_id=$_GET['account_id'];
		$pps=$_GET['purpose'];
		
		if($pps=='all'){
			$pps="'or''='";
			$purpose="ALL";
		}
		else{
			$purpose=$pps;
		}
		if($a_id=='all'){
			$a_id="'or''='";
			$account_name='ALL Accounts';
		}
		else{
			$temp=mysqli_query($connection,"SELECT  * FROM `accounts` where account_id='{$a_id}'");
			$temp=mysqli_fetch_assoc($temp);
			$account_name=$temp['name'];
		}
		$s1=$year.'-07-01';	//opening date
		$s2=($year+1).'-06-30';	//Closing date
		
		$query="select * from `cost` where date between '{$s1}' and '{$s2}' and (purpose='{$pps}') and (account_id='{$a_id}')";
		
		$costs=mysqli_query($connection,$query);
		$n1=mysqli_num_rows($costs);
		
		$query="select * from `deposit` where date between '{$s1}' and '{$s2}'  and (purpose='{$pps}') and (account_id='{$a_id}')";
			
		$deposits=mysqli_query($connection,$query);
		$n2=mysqli_num_rows($deposits);
		
		if(!$n1 && !$n2){?>
			<div id="search_result">
		No Transaction for<br><b><i><?php echo $account_name.'[ July, '.$year.' - June, '. ($year+1).']'?></i></b><br>
	Purpose: <b><?php echo ucfirst($purpose); ?></b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{
		$dt=$year.'-07-01';
		
		
		$query="select sum(total) as cost from `cost` where  date < '{$dt}'  and (account_id='{$a_id}')";
		
		$t1=mysqli_query($connection,$query);
		$t1=mysqli_fetch_assoc($t1);
		
		
		/*$gone=mysqli_query($connection,"SELECT sum(amount) as gone FROM `transfer` where date < '{$s1}' and (take_from='{$a_id}') ");
		
		$gone=mysqli_fetch_assoc($gone);
		
		$came=mysqli_query($connection,"SELECT sum(amount) as came FROM `transfer` where date < '{$s1}' and (give_to='{$a_id}') ");
		$came=mysqli_fetch_assoc($came);*/
		
		
		$query="select sum(total) as deposit  from `deposit` where  date < '{$dt}'   and (account_id='{$a_id}')";

		$t2=mysqli_query($connection,$query);
		$t2=mysqli_fetch_assoc($t2);

		/* Adding opening
		$query="select sum(opening) as opening  from `accounts` where  add_date < '{$dt}'   and (account_id='{$a_id}')";
		$t3=mysqli_query($connection,$query);
		$t3=mysqli_fetch_assoc($t3);*/


		$opening=$t2['deposit']  - $t1['cost'];
		$total_cost=0;
		$total_deposit=0;
	
?>
		<?php
			echo get_print_header();
		?>	
<table class="all-table min-broad report report">
	<caption class="caption">Transaction Information of <br><b> <?php echo $account_name.'[ July, '.$year.' -
	June, '. ($year+1).']'?></b><br>Purpose: <b><?php echo ucfirst($purpose); ?></b></caption>
	<tr>
		<th width="10%">Sl.</th>
		<th nowrap width="20%">Month</th>
		<th nowrap width="20%">Deposit</th>
		<th nowrap width="20%">Cost</th>
		<th nowrap width="30%">Balance</th>
		
	</tr>
	<tr>
		<td>-</td>
		<td><b>Opening</b></td>
		<td>-</td>
		<td>-</td>
		<td><b><?php echo get_csn($opening)?></b></td>
	</tr>
	<?php
	for($i=7;;){
		$month=$i;
		if($month<10){
			$month='0'.$month;
		}
		$search_time=$year.'-'.$month.'-';
		
		
		$query="select sum(paid) as paid from `cost` where  date like '{$search_time}%'  and (purpose='{$pps}') and (account_id='{$a_id}')";
		
		
		$costs=mysqli_query($connection,$query);
		$costs=mysqli_fetch_assoc($costs);
		
		/*finds amount transfered to another account  */
		$q1="SELECT sum(amount) as amount FROM `transfer` where (take_from='{$a_id}') and  date like '{$search_time}%' ";
		$gone=mysqli_query($connection,$q1);
		$gone=mysqli_fetch_assoc($gone);
		/*finds amount transfered to this account  */
		$q2="SELECT sum(amount) as amount FROM `transfer` where (give_to='{$a_id}') and  date like '{$search_time}%' ";
		$came=mysqli_query($connection,$q2);
		$came=mysqli_fetch_assoc($came);
		
		
		$query="select sum(paid) as paid from `deposit` where  date like '{$search_time}%'  and (purpose='{$pps}') and (account_id='{$a_id}')";
		
		
		$deposits=mysqli_query($connection,$query);
		$deposits=mysqli_fetch_assoc($deposits);
		
		/* Opening
		$query="select sum(opening) as opening  from `accounts` where  add_date like '{$search_time}%'   and (account_id='{$a_id}')";

		$op=mysqli_query($connection,$query);
		$op=mysqli_fetch_assoc($op);*/
		
		$monthly_cost=0;
		$monthly_dep=0;

			
			$monthly_cost+=$gone['amount'];
			
			$monthly_dep+=$came['amount'];
		

			$monthly_cost+=$costs['paid'];
			
			$monthly_dep+=$deposits['paid'];
			
	?>
		<tr>
			<td><?php echo $count++?></td>
			<td rowspan="2">
				<a href="view_monthly_bank_transaction.php?account_id=<?php echo $_GET['account_id']?>&year=<?php echo $year?>&month=<?php echo intval($month)?>&submit=Search">
					<?php echo get_month_name($month).', '.$year?>
				</a>
				
			</td>
			<td><?php echo get_csn($monthly_dep)?></td>
			<td>-</td>
			<?php
			$opening+=$monthly_dep;
			?>
			
			
			<td><?php echo get_csn($opening)?></td>
		</tr>
		<tr>
			<td><?php echo $count++?></td>
			<td>-</td>
			<td><?php echo get_csn($monthly_cost)?></td>
			
			<?php
			$opening-=$monthly_cost;
			?>
			
			
			<td><?php echo get_csn($opening)?></td>
		</tr>
	<?php  
		if($i==12){
			$i=1;
			$year++;
		}
		else{
			$i++;
		}
		if($i==7){
			break;
		}
		$total_cost+=$monthly_cost;
		$total_deposit+=$monthly_dep;
		}

	?>
	<tr>
		<td>-</td>
		<td><b>Closing</b></td>
		<td>-</td>
		<td>-</td>
		<td><b><?php echo get_csn($opening)?></b></td>
	</tr>
	<tr class="total_row">
		<td></td>
		<td>Total</td>
		<td><?php echo get_csn($total_deposit)?></td>
		<td><?php echo get_csn($total_cost)?></td>
		<td></td>
	</tr>
	<tr class="num-word">
		
		<td></td>
		<td></td>
		<td><?php echo num_to_words($total_deposit)?></td>
		<td><?php echo num_to_words($total_cost)?></td>
		<td><?php echo num_to_words($opening)?></td>
	</tr>
</table>
<br>
<div class="years-cpt" align="center"><b>www.yearstech.com</b></div>
<br>
<p  align="center" style="width:80%;margin:0px auto">
			<input type="button" id="print" title="Print" onClick="window.print()" value="Print">
</p>

<br>
	<?php 
		}	
	}
	?>
<?php require_once("includes/footer.php"); ?>
