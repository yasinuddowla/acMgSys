<?php require_once("includes/header.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
	if(!isset($_COOKIE['admin'])&&!isset($_COOKIE['account'])){
		redirect_to('login.php');
	}
?>

 <?php
admin_nav();
$purposes=mysqli_query($connection,"SELECT  * FROM `purposes` where 1");

$party=mysqli_query($connection,"SELECT  * FROM `party` ");
 ?> 
	<div class="io-form" style="width:70%;margin:0px auto">
    <form  class="search-form" method="GET">
	<p align="center" style="color: #F00; font-size: 1.2em;">Show Party Transaction</p>
	<p>
		<div class="label">Select Party<span class="star">* </span></div>
		<select  type="text" name="party_id" value="" required tabindex="1" >
			<option disabled="disabled"  selected="selected">Select Party</option>
			<?php while ($row=mysqli_fetch_assoc($party)) { ?>
			<option value="<?php echo $row['party_id']?>"><?php echo $row['name']?></option>
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
?>
<?php
	if(isset($_GET['purpose'])) { 
	

		$pps=$_GET['purpose'];
		$party_id=$_GET['party_id'];
		
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
		

		$query="SELECT * FROM `cost` WHERE date BETWEEN '{$s1}' AND '{$s2}' AND (purpose='{$pps}') AND party_id='{$party_id}' order by date asc";
		$cost=mysqli_query($connection,$query);
		
		$query="SELECT * FROM `deposit` WHERE date BETWEEN '{$s1}' AND '{$s2}' AND (purpose='{$pps}') AND party_id='{$party_id}' order by date asc";
			
		$deposit=mysqli_query($connection,$query);
		

		

		$i=mysqli_num_rows($deposit);
		$j=mysqli_num_rows($cost);

		if($s1 == $s2){
			$duration = format_date($s1);
		}
		else{
			$duration = format_date($s1).'] - ['. format_date($s2);
		}

		if(!$i && !$j){?>
			<div id="search_result">
				No Transaction for <b><?php echo getParty($party_id)['name']?></b><br>on <b><?php echo '['.$duration.']'?></b><br>[Purpose: <b><?php echo ucfirst($purpose); ?>]</b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{
			$total_ct_paid=0;
			$total_ct_due=0;

			$total_dp_paid=0;
			$total_dp_due=0;

			$total_cost=0;
			$total_deposit=0;

	
?>
		<?php
			echo get_print_header();
		?>	

<table class="all-table full-broad report party">
	<caption class="caption">Transaction Information for <b><?php echo getParty($party_id)['name']?></b><br>On <b><?php echo '['.$duration.']'?></b><br>Purpose: <b><?php echo ucfirst($purpose); ?></b></caption>
	<tr>
		<th width="6%" rowspan="2">SL</th>
		<th width="10%" rowspan="2">Date</th>
		<th width="12%" rowspan="2">Purpose</th>
		<th  colspan="3">Deposits</th>
		<th  colspan="3">Costs</th>
	</tr>
	<tr>
		<th width="12%">Total</th>
		<th width="12%">Paid</th>
		<th width="12%">Due</th>
		<th width="12%">Total</th>
		<th width="12%">Paid</th>
		<th width="12%">Due</th>

	</tr>
	
	<?php 
	
		$i=mysqli_num_rows($deposit);
		$j=mysqli_num_rows($cost);
		
		$r1=mysqli_fetch_assoc($deposit);
		$r2=mysqli_fetch_assoc($cost);
		
		$n=$i + $j ;
		
		//current deposit date
		$cddt=$s2;
		if(isset($r1['date']))
			$cddt=$r1['date'];
		//current cost date
		$ccdt=$s2;
		if(isset($r2['date']))
			$ccdt=$r2['date'];
		
		
		while($n){
			if($i>0){
				
				if(($cddt <= $ccdt ) ){
						$total_deposit+=$r1['total'];
						$total_dp_paid+=$r1['paid'];
						$total_dp_due+=$r1['due'];

				?>
					<tr>
						<td><?php echo $count++?></td>
						<td rowspan="<?php echo $span?>"><?php echo ($r1['date'])?></td>
						
						<td><?php echo $r1['purpose']?></td>
						
						<td><?php echo get_csn($r1['total'])?></td>
						<td><?php echo get_csn($r1['paid'])?></td>
						<td><?php echo get_csn($r1['due'])?></td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						
					
					</tr>
					<?php 
					$i--;
					$n--;
					$r1=mysqli_fetch_assoc($deposit);
					
					//current deposit date
					$cddt=$s2;
					if(isset($r1['date']))
						$cddt=$r1['date'];
				}
				
			}
			
			if($j>0){
				
				if(($ccdt <= $cddt ) ){
						$total_cost+=$r2['total'];
						$total_ct_paid+=$r2['paid'];
						$total_ct_due+=$r2['due'];
					?>
					<tr>
						<td><?php echo $count++?></td>
						<td rowspan="<?php echo $span?>"><?php echo ($r2['date'])?></td>
						
						<td><?php echo $r2['purpose']?></td>
						
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td><?php echo get_csn($r2['total'])?></td>
						<td><?php echo get_csn($r2['paid'])?></td>
						<td><?php echo get_csn($r2['due'])?></td>
						
						
					</tr>
					<?php
					$n--;			
					$j--;
					$r2=mysqli_fetch_assoc($cost);
					
					//current cost date
					$ccdt=$s2;
					if(isset($r2['date']))
						$ccdt=$r2['date'];
				
				}
			}
			
			
		}
	?>
	
	<tr >
		<td colspan="3" style="text-align:right;padding-right:100px;"><b>Total</b></td>
		<td><b><?php echo get_csn($total_deposit)?></b></td>
		<td><b><?php echo get_csn($total_dp_paid)?></b></td>
		<td><b><?php echo get_csn($total_dp_due)?></b></td>
		<td><b><?php echo get_csn($total_cost)?></b></td>
		<td><b><?php echo get_csn($total_ct_paid)?></b></td>
		<td><b><?php echo get_csn($total_ct_due)?></b></td>
		
	</tr>
	<?php
	/*
	?>
	<tr >
		
		<td colspan="1"><?php echo num_to_words($total_deposit)?></td>
		<td colspan="1"><?php echo num_to_words($total_dp_paid)?></td>
		<td colspan="1"><?php echo num_to_words($total_dp_due)?></td>
		<td colspan="1"><?php echo num_to_words($total_cost)?></td>
		<td colspan="1"><?php echo num_to_words($total_ct_paid)?></td>
		<td colspan="1"><?php echo num_to_words($total_ct_due)?></td>
		
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
