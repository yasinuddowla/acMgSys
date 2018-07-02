<?php require_once("includes/header.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
	login(1,1);
?>

 <?php
admin_nav();
$purposes=mysqli_query($connection,"SELECT  * FROM `purposes` where type='both' or type='cost'");
 ?> 
	<p align="cenetr" id="heading" style="margin:0px 320px 0px 320px">
		
	</p>
	<br>
	<div style="width:70%;margin:0px auto">
    <form action="view_monthly_purpose_cost.php" id="select_form" method="GET">
	<p align="center" style="color: #F00; font-size: 1.2em;">Show Monthly Purpose Based Costs</p>
	<p>
		<label style="width:250px;float:left">Select Purpose  <span style="color: #F00; font-size: 1em;">*</span></label>:
		<select  type="text" name="purpose" value="" required tabindex="5" style="width:358px" >
			<option value="" disabled="disabled" selected="selected">Select Purpose</option>
						<?php while ($row=mysqli_fetch_assoc($purposes)) { ?>
						<option value="<?php echo $row['purpose']?>" ><?php echo $row['purpose']?></option>
						<?php } ?>
		</select>
	</p>


	<p >
       <label style="width:250px;float:left">Enter Month of a Year<span style="color: #F00; font-size: 12px;">* </span></label>:
	   <input type="number" name="year" required style="width:4em" tabindex="1" max="2100" min="2000" value="" placeholder="YYYY"/>
	   <input type="number" name="month"  required style="width:4em" tabindex="1" min="1" max="12" value=""placeholder="MM" />
		<input type="submit" name="submit" id="simple_Search_btn" tabindex="1" value="Search" />
	</p>
  </form>
  </div>
  <br/>

<?php
$costs=mysqli_query($connection,"SELECT  * FROM `cost` ");


$count=1;
?>
<?php
	if(isset($_GET['submit'])) { 

		$purpose=$_GET['purpose'];
		$year=$_GET['year'];
		$month=$_GET['month'];
		if($month<10){
			$month='0'.$month;
		}
		$search_time=$year.'-'.$month.'-';
		$query="select * from `cost` where date like '{$search_time}%' and purpose='{$purpose}'";
		$costs=mysqli_query($connection,$query);
		$num_rows=mysqli_num_rows($costs);
		if(!$num_rows){?>
			<div id="search_result">
								No Costs in <b><?php echo $purpose.' ['.get_month_name($month).', '.$year.' ]'?></b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{
			$total=0;
?>
<table class="all-table full-broad">
		<caption class="caption">Cost Information of <b>  <?php echo $purpose.' ['.get_month_name($month).', '.$year.' ]'?> </b></caption>
	<tr>
		<th width="5%">SL</th>
		<th width="10%">ID</th>
		<th width="20%">Reciever's Name</th>
		<th width="20%" >Description</th>
		<th width="13%">Date</th>
		<th width="20%">Account Name</th>
		<th nowrap width="12%">Amount</th>
	</tr>
	<?php while ($row=mysqli_fetch_assoc($costs)) { 
	$temp=mysqli_query($connection,"SELECT  * FROM `accounts` where id='{$row ['account_id']}' ");
	$temp=mysqli_fetch_assoc($temp);
	$day=$row['date'][8].$row['date'][9];
	$total+=$row['amount'];
	?>
		<tr>
			<td><?php echo $count++?></td>
			<td>
				<a href="preview_voucher.php?type=Cost&q=<?php echo $row['id']?>&submit=Search">
			<?php echo $row['id']?>
			</a>
			
			
			</td>
			<td><?php echo $row['name']?></td>
			<td><?php echo $row['description']?></td>
			<td>
				<a href="view_daily_cost.php?y1=<?php echo $year?>&m1=<?php echo intval($month)?>&d1=<?php echo intval($day)?>&y2=<?php echo $year?>&m2=<?php echo intval($month)?>&d2=<?php echo intval($day)?>&submit=Search">
					<?php echo $row['date']?>
				</a>
			</td>
			<td><?php echo $temp['name']?></td>
			<td><?php echo $row['amount']?></td>

		</tr>
	<?php } ?>
	<tr class="total_row">
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td>Total</td>
		<td><?php echo $total?></td>
	</tr>
	<tr class="num-word">
		<td colspan="2"></td>
		
		<td colspan="5"><?php echo num_to_words($total)?></td>
	</tr>
</table>
<br>
<br>
	<?php } 
	}
	?>
<?php require_once("includes/footer.php"); ?>
