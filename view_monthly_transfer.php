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
    <form action="view_monthly_transfer.php" id="select_form" method="GET">
	<p align="center" style="color: #F00; font-size: 1.2em;">Show Monthly Transfer</p>
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
		$query="select * from `transfer` where date like '{$search_time}%' ";
		$costs=mysqli_query($connection,$query);
		$num_rows=mysqli_num_rows($costs);
		if(!$num_rows){?>
			<div id="search_result">
				No Tranfers in <b><?php echo get_month_name($month).', '.$year?></b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{
			$total=0;
?>
<table class="all-table min-broad">
	<caption class="caption">Tranfer Information of <b> [ <?php echo get_month_name($month).', '.$year?> ]</b></caption>
	<tr>
		<th width="10%">Sl</th>
		<th width="20%">Date</th>
		<th width="25%">From</th>
		<th width="25%">To</th>
		<th nowrap width="20%">Amount</th>
	</tr>
	<?php while ($row=mysqli_fetch_assoc($costs)) { 
	$temp=mysqli_query($connection,"SELECT  * FROM `accounts` where account_id='{$row ['take_from']}' ");
	$a_name1=mysqli_fetch_assoc($temp);
	$temp=mysqli_query($connection,"SELECT  * FROM `accounts` where account_id='{$row ['give_to']}' ");
	$a_name2=mysqli_fetch_assoc($temp);
	$day=$row['date'][8].$row['date'][9];
	$total+=$row['amount'];
	?>
		<tr>
			<td><?php echo $count++?></td>
			<td><?php echo format_date($row['date'])?></td>
			<td><?php echo $a_name1['name']?></td>
			<td><?php echo $a_name2['name']?></td>
			<td><?php echo get_csn($row['amount'])?></td>

		</tr>
		
	<?php } ?>
	<tr class="total_row">
					<td ></td>
					<td ></td>
					<td ></td>
					<td >Total</td>
					<td ><?php echo get_csn($total)?></td>
				</tr>
				<tr class="num-word">
					<td colspan="3"></td>
					
					<td colspan="2"><?php echo num_to_words($total)?></td>
				</tr>
</table>
<br>
<br>
	<?php } 
	}
	?>
<?php require_once("includes/footer.php"); ?>
