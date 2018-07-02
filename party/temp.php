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
    <form action="view_daily_bank_transaction.php" class="search-form" method="GET">
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
		

		
	
		$query="SELECT sum(total) as deposit FROM `deposit` where date < '{$s1}' and (party_id='{$party_id}')";
		
		
		
		
		$t2=mysqli_query($connection,$query);
		$t2=mysqli_fetch_assoc($t2);

		$gone=mysqli_query($connection,"SELECT sum(amount) as amount FROM `transfer` where date < '{$s1}' and (take_from='{$a_id}') ");
		$gone=mysqli_fetch_assoc($gone);
		
		$came=mysqli_query($connection,"SELECT sum(amount) as amount FROM `transfer` where date < '{$s1}' and (give_to='{$a_id}' )");
		$came=mysqli_fetch_assoc($came);

		$opening=$t2['deposit'] - $t1['cost'] +$came['amount'] - $gone['amount'] ;
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
				No Transaction for <b><?php echo $account_name?></b><br>on <b><?php echo '['.$duration.']'?></b><br>[Purpose: <b><?php echo ucfirst($purpose); ?>]</b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{
			$total_cost=0;
			$total_deposit=0;
	
?>
		<div class="print-header">
			<div class="h1">ARAB BANGLADESH OVERSEAS & HAJJ GROUP</div>
			<div class="add">
				186/200, KHWAJA MARKET (3DR   FLOOR), C.D.A AVENUE, MURADPUR, PANCHLAISH 
				CHITTAGONG. PHONE: 2557210, MOBILE: 01816440555, 01911579431
			</div>
			<br>
		</div>

<table class="all-table full-broad report">
	<caption class="caption">Transaction Information for <b><?php echo $account_name?></b><br>On <b><?php echo '['.$duration.']'?></b><br>Purpose: <b><?php echo ucfirst($purpose); ?></b></caption>
	<tr>
		<th width="5%">SL</th>
		<th width="10%">Date</th>
		<th width="15%">Purpose</th>
		<th width="19%">Description</th>
		<th width="13%">Deposits</th>
		<th width="13%">Costs</th>
		<th width="15%">Balance</th>
	</tr>
	<tr>
		<td>-</td>
		<td>-</td>
		<td>-</td>
		<td><b>Opening</b></td>
		<td>-</td>
		<td>-</td>
		<td><b><?php echo get_csn($opening)?></b></td>
	</tr>
	<?php 
	
		$i=mysqli_num_rows($deposit);
		$j=mysqli_num_rows($cost);
		
		$r1=mysqli_fetch_assoc($deposit);
		$r2=mysqli_fetch_assoc($cost);
		$gone=mysqli_query($connection,"SELECT * FROM `transfer` where date between '{$s1}' and '{$s2}' and (take_from='{$a_id}') ");
		$came=mysqli_query($connection,"SELECT * FROM `transfer` where date between '{$s1}' and '{$s2}' and (give_to='{$a_id}') ");
		
		$numcame=mysqli_num_rows($came);
		$numgone=mysqli_num_rows($gone);
		$n=$i + $j + $numcame + $numgone;
		$rgone=mysqli_fetch_assoc($gone);
		$rcame=mysqli_fetch_assoc($came);
		
		//current deposit date
		$cddt=$s2;
		if(isset($r1['date']))
			$cddt=$r1['date'];
		//current cost date
		$ccdt=$s2;
		if(isset($r2['date']))
			$ccdt=$r2['date'];
		
		// current transfer (came) date
		$cameDate=$s2;
		if(isset($rcame['date']))
			$cameDate=$rcame['date'];
		
		// current transfer (gone) date
		$goneDate=$s2;
		if(isset($rgone['date']))
			$goneDate=$rgone['date'];
		
		while($n){
			if($i>0){
				
				if(($cddt <= $ccdt && $cddt <= $cameDate && $cddt <= $goneDate) ){
						$total_deposit+=$r1['total'];
				?>
					<tr>
						<td><?php echo $count++?></td>
						<td rowspan="<?php echo $span?>"><?php echo format_date($r1['date'])?></td>
						
						<td><?php echo $r1['purpose']?></td>
						<td><?php echo $r1['description']?></td>
						<td><?php echo get_csn($r1['total'])?></td>
						<td>-</td>
						
						<?php
						$opening+=$r1['total'];
						?>
						
						
						<td><?php echo get_csn($opening)?></td>
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
			if($numcame>0&&($cameDate <= $ccdt && $cameDate <= $cddt && $cameDate <= $goneDate)){
				$total_deposit+=$rcame['amount'];

			?>
				<tr>
					<td><?php echo $count++?></td>
					<td><?php echo format_date($rcame['date'])?></td>
					<td><i>Transfered</i></td>
					<td><i>From: <b><?php echo getacname($rcame['take_from'])?></b></i></td>
					<td><?php echo get_csn($rcame['amount'])?></td>
					<td>-</td>
					<?php
					$opening+=$rcame['amount'];
					?>
					<td><?php echo get_csn($opening)?></td>
				</tr>
				
					<?php $rcame=mysqli_fetch_assoc($came);
					$numcame--;
					$n--;
					// current transfer (came) date
					$cameDate=$s2;
					if(isset($rcame['date']))
						$cameDate=$rcame['date'];
					

		}
			if($j>0){
				
				if(($ccdt <= $cddt && $ccdt <= $cameDate && $ccdt <= $goneDate) ){
						$total_cost+=$r2['total'];
					?>
					<tr>
						<td><?php echo $count++?></td>
						<td rowspan="<?php echo $span?>"><?php echo format_date($r2['date'])?></td>
						
						<td><?php echo $r2['purpose']?></td>
						<td><?php echo $r2['description']?></td>
						<td>-</td>
						<td><?php echo get_csn($r2['total'])?></td>
						
						<?php
						$opening-=$r2['total'];
						?>
						
						
						<td><?php echo get_csn($opening)?></td>
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
			if($numgone>0&&($goneDate <= $ccdt && $goneDate <= $cddt && $goneDate<= $cameDate)){
				$total_cost+=$rgone['amount'];

			?>
				<tr>
					<td><?php echo $count++?></td>
					<td><?php echo format_date($rgone['date'])?></td>
					<td><i>Transfered</i></td>
					<td><i>To: <b><?php echo getacname($rgone['give_to'])?></b></i></td>
					<td>-</td>
					<td><?php echo get_csn($rgone['amount'])?></td>
					
					<?php
					$opening-=$rgone['amount'];
					?>
					<td><?php echo get_csn($opening)?></td>
				</tr>
				
				<?php $rgone=mysqli_fetch_assoc($gone);
					$numgone--;
					$n--;
					// current transfer (gone) date
					$goneDate=$s2;
					if(isset($rgone['date']))
						$goneDate=$rgone['date'];
				
			}
			
		}
	?>
	<tr>
		<td>-</td>
		<td>-</td>
		<td>-</td>
		<td><b>Closing</b></td>
		<td>-</td>
		<td>-</td>
		<td><b><?php echo get_csn($opening)?></b></td>
	</tr>

	<tr >
		<td colspan="4" rowspan="2" style="text-align:right;padding-right:100px;"><b>Total</b></td>
		<td><b><?php echo get_csn($total_deposit)?></b></td>
		<td><b><?php echo get_csn($total_cost)?></b></td>
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
