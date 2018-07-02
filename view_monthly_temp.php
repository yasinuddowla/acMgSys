<?php require_once("includes/header.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
	login(1,1);
?>

 <?php
admin_nav();
	
 ?> 
	<p align="cenetr" id="heading" style="margin:0px 320px 0px 320px">
		
	</p>
	<br>
	<div id="form_area">
    <form action="view_monthly_temp.php" id="select_form" method="GET">
	<p align="center" style="color: #F00; font-size: 1.2em;">Show Monthly Temporary Vouchers</p>
	<p align="center">
       <label >Enter Month of a Year<span style="color: #F00; font-size: 12px;">* </span></label>:
	   <input type="number" name="year" required style="width:4em" tabindex="1" max="2100" min="2000" value="" placeholder="YYYY"/>
	   <input type="number" name="month"  required style="width:4em" tabindex="1" min="1" max="12" value=""placeholder="MM" />
		<input type="submit" name="submit" id="simple_Search_btn" tabindex="1" value="Search" />
	</p>
  </form>
  </div>
  <br/>

<?php
$costs=mysqli_query($connection,"SELECT  * FROM `temp` ");


$count=1;
?>
<?php
	if(isset($_GET['submit'])) { 
	
		$year=$_GET['year'];
		$month=$_GET['month'];
		if($month<10){
			$month='0'.$month;
		}
		$search_time=$year.'-'.$month.'-';
		$query="select * from `temp` where date like '{$search_time}%' ";
		$costs=mysqli_query($connection,$query);
		$num_rows=mysqli_num_rows($costs);
		if(!$num_rows){?>
			<div id="search_result">
				No Vouchers in <b><?php echo get_month_name($month).', '.$year?></b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{
			$total=0;
?>
<table class="all-table full-broad">
	<caption class="caption">Temporary Voucher Information of <b> [ <?php echo get_month_name($month).', '.$year?> ]</b></caption>
	<tr>
		<th width="6%">Sl. No</th>
		<th width="8%">ID</th>
		<th width="12%">Purpose</th>
		<th width="16%">Reciever's Name</th>
		<th width="16%" >Description</th>
		<th width="13%">Date</th>
		<th width="17%">Account Name</th>
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
				<a href="preview_voucher.php?type=temp&q=<?php echo $row['id']?>&submit=Search">
			<?php echo $row['id']?>
			</a>
			
			
			</td>
			<td>
			<?php echo $row['purpose']?>
			</td>
			<td><?php echo $row['name']?></td>
			<td><?php echo $row['description']?></td>
			<td>
					<?php echo $row['date']?>
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
		<td></td>
		<td>Total</td>
		<td><?php echo $total?></td>
	</tr>
	<tr class="num-word">
		<td colspan="5"></td>
		
		<td colspan="3"><?php echo num_to_words($total)?></td>
	</tr>
</table>
<br>
<br>
	<?php } 
	}
	?>
<?php require_once("includes/footer.php"); ?>
