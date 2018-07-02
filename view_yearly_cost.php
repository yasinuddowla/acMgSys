<?php require_once("includes/header.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
	login(1,1);
?>

 <?php
admin_nav();
$years = mysqli_query($connection,"SELECT  distinct YEAR(date) as year FROM `cost` UNION SELECT  distinct YEAR(date) as year FROM `cost` ");

$purposes=mysqli_query($connection,"SELECT  * FROM `purposes` where type='Both' or type='Cost'");

$accounts=mysqli_query($connection,"SELECT  * FROM `accounts` ");
 ?> 
	<br>
<div class="io-form" style="width:70%;margin:0px auto">
    <form action="view_yearly_cost.php" class="search-form" method="GET">
		<p align="center" style="color: #F00; font-size: 1.2em;">Show Yearly Cost</p>

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
			
			$account_name=getaccount($a_id)['name'];
		}
		$s1=$year.'-07-01';	//opening date
		$s2=($year+1).'-06-30';	//Closing date
		
		$query="select * from `cost` where date between '{$s1}' and '{$s2}' and (purpose='{$pps}') and (account_id='{$a_id}')";
		
		$costs=mysqli_query($connection,$query);
		$n1=mysqli_num_rows($costs);
		
		if(!$n1){?>
			<div id="search_result">
		No Cost for<br><b><i><?php echo $account_name.'[ July, '.$year.' - June, '. ($year+1).']'?></i></b><br>
	Purpose: <b><?php echo ucfirst($purpose); ?></b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{
			$total=0;
	
?>
		<?php
			echo get_print_header();
		?>	

<table class="all-table min-broad  y-report report">
		<caption class="caption">Cost Information of <br><b> <?php echo $account_name.'[ July, '.$year.' -
	June, '. ($year+1).']'?></b><br>Purpose: <b><?php echo ucfirst($purpose); ?></b></caption>

	<tr>
		<th width="15%">Sl</th>
		<th nowrap width="45%">Month</th>
		<th nowrap width="40%">Monthly Total</th>
		
	</tr>
	<?php
	for($i=7;;){
		$month=$i;
		if($month<10){
			$month='0'.$month;
		}
		$search_time=$year.'-'.$month.'-';
		
		/*finds amount transfered to this account  */
		$q2="SELECT sum(amount) as amount FROM `transfer` where (give_to='{$a_id}') and  date like '{$search_time}%' ";
		$came=mysqli_query($connection,$q2);
		$came=mysqli_fetch_assoc($came);
		
		
		$query="select sum(total) as total from `cost` where  date like '{$search_time}%'  and (purpose='{$pps}') and (account_id='{$a_id}')";
		
		
		$costs=mysqli_query($connection,$query);
		$costs=mysqli_fetch_assoc($costs);
			$monthly_total=0;
			$monthly_total+=$came['amount'];
			$monthly_total+=$costs['total'];
	?>
		<tr>
			<td><?php echo $count++?></td>
			<td>
				<a href="view_monthly_cost.php?purpose=<?php echo $_GET['purpose']?>&account_id=<?php echo $_GET['account_id']?>&year=<?php echo $year?>&month=<?php echo intval($month)?>&submit=Search">
					<?php echo get_month_name($month).', '.$year?>
				</a>
				
			</td>
			<td><?php echo get_csn($monthly_total)?></td>
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
		$total+=$monthly_total;
	}

	?>
	<tr class="total_row">
		<td></td>
		<td><b>Total</b></td>
		<td><b><?php echo get_csn($total)?></b></td>
	</tr>
	<tr class="num-word">
		
		<td colspan="3"><?php echo num_to_words($total)?></td>
	</tr>
</table>
<br>
<br>
<br>
<div class="years-cpt" align="center"><b>www.yearstech.com</b></div>
<br>

<p  align="center" style="width:80%;margin:0px auto">
			<input type="button" id="print" title="Print" onClick="window.print()" value="Print">
</p>
<br>
<br>
	<?php 
		}	
	}
	?>
<?php require_once("includes/footer.php"); ?>
