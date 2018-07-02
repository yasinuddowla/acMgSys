<?php require_once("includes/header.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
	login(1,1);
?>

 <?php
admin_nav();
$purposes=mysqli_query($connection,"SELECT  * FROM `purposes` where type='Both' or type='Cost'");
$accounts=mysqli_query($connection,"SELECT  * FROM `accounts` ");

 ?> 
<div class="io-form" style="width:70%;margin:0px auto">
    <form action="view_daily_cost.php" class="search-form" method="GET">
	<p align="center" style="color: #F00; font-size: 1.2em;">Show Daily Cost</p>
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
?>
<?php
	if(isset($_GET['purpose'])) { 
	
		$pps=$_GET['purpose'];
		$a_id=$_GET['account_id'];
		$temp_pps=$_GET['purpose'];
		$temp_a_id=$_GET['account_id'];

		
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
		if($a_id=='all'){
			$a_id="'or''='";
			$account_name='ALL Accounts';
		}
		else{
			
			$account_name=getaccount($a_id)['name'];
		}
		$query="SELECT * FROM `cost` WHERE date BETWEEN '{$s1}' and '{$s2}' AND (purpose='{$pps}') and (account_id='{$a_id}') order by date asc";
			
		$costs=mysqli_query($connection,$query);
		$num_rows=mysqli_num_rows($costs);

		if($s1 == $s2){
			$duration = format_date($s1);
		}
		else{
			$duration = format_date($s1).'] - ['. format_date($s2);
		}

		if(!$num_rows){?>
			<div id="search_result">
				No Cost for <b><?php echo $account_name?></b><br>on <b><?php echo '['.$duration.']'?></b><br>[Purpose: <b><?php echo ucfirst($purpose); ?>]</b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{
			$total=$paid=$due=0;
?>
		<?php
			echo get_print_header();
		?>	

<?php
$th='<table class="all-table full-broad report">';
	$tc='<caption class="caption">Cost Information for <b>'.$account_name.'</b><br>On <b> ['.
	$duration.']</b><br>Purpose: <b>'.ucfirst($purpose).'</b></caption>';

	$tr='<tr>
		<th width="6%">Sl</th>
		<th width="6%">ID</th>
		<th width="14%">Party Name</th>
		<th width="12%">Date</th>
		<th width="12%">Purpose</th>
		<th width="12%">Account Name</th>
		<th width="12%" >Total</th>
		<th width="12%">Paid</th>
		<th width="12%">Due</th>
	</tr>';
	?>
	<?php
	$pr=6; //no of  rows on a page
		$pages=ceil($num_rows/$pr);
		
					//echo '<div class="no-break">';
			
				echo '<div class="report-div">';
			
			echo $th;
			echo $tc;
			echo $tr;

			for($i=1;$i<=$num_rows;$i++){
			$row=mysqli_fetch_assoc($costs);
			$total+=$row['total'];
			$paid+=$row['paid'];
			$due+=$row['due'];
			?>
		<tr>
			<td><?php echo $count++?></td>
			<td>
				<a href="preview_voucher.php?type=Cost&q=<?php echo $row['id']?>&submit=Search">
			<?php echo $row['id']?>
			</a>
			

			<td>
				<a href="party/index.php?from=<?php echo $s1?>&to=<?php echo $s2?>&purpose=<?php echo $_GET['purpose']?>&party_id=<?php echo $row['party_id']?>&submit=Search">
						<?php echo getParty($row['party_id'])['name']?>
					</a>
				
				
			</td>
			
			</td>
			<td>
				<a href="view_daily_cost.php?from=<?php echo $row['date']?>&to=<?php echo $row['date']?>&purpose=<?php echo $_GET['purpose']?>&account_id=<?php echo $_GET['account_id']?>&submit=Search">
					<?php echo format_date($row['date'])?>
				</a>
			</td>
			<td>
				<a href="view_daily_cost.php?from=<?php echo $s1?>&to=<?php echo $s2?>&purpose=<?php echo $_GET['purpose']?>&account_id=<?php echo $_GET['account_id']?>&submit=Search">
					<?php echo $row['purpose']?>
				</a>
			</td>
			<td><?php echo getaccount($row['account_id'])['name']?></td>
			<td><?php echo $row['total']?></td>
			<td><?php echo $row['paid']?></td>
			<td><?php echo $row['due']?></td>
			
			
			

		</tr>
		
		<?php  
		}
	
		//if($num_rows==0){
			//$tb='<table class="all-table full-broad report">
			$tb='<tr class="total_row">
					
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td width="12%">Total</td>
					<td width="12%">'.get_csn($total).'</td>
					<td width="12%">'.get_csn($paid).'</td>
					<td width="12%">'.get_csn($due).'</td>

				</tr>
				<tr class="num-word">
					<td colspan="6"></td>
					
					<td >'.num_to_words($total).'</td>
					<td >'.num_to_words($paid).'</td>
					<td >'.num_to_words($due).'</td>

				</tr>';
				//</table>';
					echo $tb;
	
		echo '</table><br>';
	  	echo '<div class="years-cpt" align="center"><b>www.yearstech.com</b></div>';
		//echo '<p class="page-no">'.$page++.' of '.$pages.'</p>';
		echo '</div><br><br><br>';
	  ?>
<br>
<p  align="center" style="width:80%;margin:0px auto">
			<input type="button" id="print" title="Print" onClick="window.print()" value="Print">
</p>
	<?php } 
	}
	?>
<?php require_once("includes/footer.php"); ?>
