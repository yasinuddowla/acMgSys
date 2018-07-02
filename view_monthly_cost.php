<?php require_once("includes/header.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
	login(1,1);
?>

 <?php
admin_nav();
$years = mysqli_query($connection,"SELECT  distinct YEAR(date) as year FROM `cost` UNION SELECT  distinct YEAR(date) as year FROM `cost` ");

$purposes=mysqli_query($connection,"SELECT  * FROM `purposes` where type='Both' or type='cost'");
$accounts=mysqli_query($connection,"SELECT  * FROM `accounts` ");

 ?> 
<div class="io-form" style="width:70%;margin:0px auto">
    <form action="view_monthly_cost.php" class="search-form" method="GET">
		<p align="center" style="color: #F00; font-size: 1.2em;">Show Monthly Cost</p>

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
$costs=mysqli_query($connection,"SELECT  * FROM `cost` ");


$count=1;
?>
<?php
	if(isset($_GET['submit'])) { 
	$pps=$_GET['purpose'];
	$a_id=$_GET['account_id'];
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
		$year=intval($_GET['year']);
		$month=intval($_GET['month']);
		$pps=$_GET['purpose'];

	
		$search_time=$year.'-'.$month.'-';
		

		$query="SELECT * FROM `cost` WHERE date LIKE '{$search_time}%' AND (purpose='{$pps}') and (account_id='{$a_id}') order by date asc";
			
		$cost=mysqli_query($connection,$query);

		$num_rows=mysqli_num_rows($costs);
		if(!$num_rows){?>
			<div id="search_result">
				No Deposit for<br><b><i><?php echo $account_name.' ['. get_month_name($month).', '.$year.']'?></i></b><br>	Purpose: <b><?php echo ucfirst($purpose); ?></b>

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
	$tc='<caption class="caption">Cost Information of <br><b><i>'.$account_name.' ['. get_month_name($month).', '.$year.']</i></b><br>Purpose: <b>'.ucfirst($purpose).'</b></caption>';

	$tr='<tr>
		<th width="6%">Sl</th>
		<th width="6%">ID</th>
		<th width="12%">Date</th>
		<th width="12%">Purpose</th>
		<th width="14%">Donor\'s Name</th>
		<th width="12%">Account Name</th>
		<th width="12%" >Total</th>
		<th width="12%">Paid</th>
		<th width="12%">Due</th>
		
	</tr>';
	?>
	<?php
	$pr=6; //no of  rows on a page
		$pages=ceil($num_rows/$pr);
		$page=1;
     
					//echo '<div class="no-break">';
			
			echo '<div class="report-div">';
			echo $th;
			if($page==1){
				echo $tc;
			}
			echo $tr;

			for($i=1;$i<=$num_rows;$i++){
			$row=mysqli_fetch_assoc($costs);
				
			
			$day=$row['date'][8].$row['date'][9];
			$total+=$row['total'];
			$paid+=$row['paid'];
			$due+=$row['due'];
			
			?>
		<tr>
			<td><?php echo $count++?></td>
			<td>
				<a href="preview_voucher.php?type=cost&q=<?php echo $row['id']?>&submit=Search">
			<?php echo $row['id']?>
			</a>
			
			
			</td>
			<td>
				<a href="view_daily_cost.php?purpose=<?php echo $pps?>&account_id=<?php echo $_GET['account_id']?>&from=<?php echo $row['date']?>&to=<?php echo $row['date']?>&submit=Search">
					<?php echo  format_date($row['date'])?>
				</a>
			</td>
			<td>
				<a href="view_monthly_cost.php?purpose=<?php echo $row['purpose']?>&account_id=<?php echo $_GET['account_id']?>&year=<?php echo $year?>&month=<?php echo intval($month)?>&submit=Search">
					<?php echo $row['purpose']?>
				</a>
			
			
			</td>
			<td><?php echo $row['name']?></td>
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
		//}
		echo '</table><br>';
	  	//echo '<div class="years-cpt" align="center"><b>www.yearstech.com</b></div>';
		//echo '<p class="page-no">'.$page++.' of '.$pages.'</p>';
		echo '</div><br><br><br>';
	  //}
	  ?>
<br>
<div class="years-cpt" align="center"><b>www.yearstech.com</b></div>
<br>


<p  align="center" style="width:80%;margin:0px auto">
			<input type="button" id="print" title="Print" onClick="window.print()" value="Print">
</p>

<?php
?>
<br>
	<?php } 
	}
	?>
<?php require_once("includes/footer.php"); ?>
