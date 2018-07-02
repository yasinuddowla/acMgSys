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
	<div id="form_area" >
    <form action="view_daily_cost.php" id="select_form" method="GET">
	<p align="center" style="color: #F00; font-size: 1.2em;">Show Daily Costs</p>
	
	<p align="center">
       <label >Enter Date<span style="color: #F00; font-size: 12px;">* </span></label>:
	   <input type="number" name="year" id="change_year" required style="width:4em" tabindex="1" max="2100" min="2000" value="" placeholder="YYYY"/>
	   <input type="number" name="month" id="change_month" required style="width:4em" tabindex="1" min="1" max="12" value=""placeholder="MM" />
	   <input type="number" name="day" id="change_day" required style="width:4em" tabindex="1" min="1" max="0" value="" onfocus="find_days_for_month()" placeholder="DD" />
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
	
		$year=intval($_GET['year']);
		$month=intval($_GET['month']);
		$day=intval($_GET['day']);
		if($month<10){
			$month='0'.$month;
		}
		if($day<10){
			$day='0'.$day;
		}
		$search_time=$year.'-'.$month.'-'.$day;
		$query="select * from `cost` where date like '{$search_time}' ";
		$costs=mysqli_query($connection,$query);
		$num_rows=mysqli_num_rows($costs);
		if(!$num_rows){?>
			<div id="search_result">
				No Costs Information on <b><?php echo $search_time?></b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{
			$total=0;
	
?>
<table class="all-table full-broad">
	<caption class="caption">Cost Information on <b> [ <?php echo $search_time?> ]</b></caption>
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
	$total+=$row['amount'];
	?>
		<tr>
			<td><?php echo $count++?></td>
			<td><?php echo $row['id']?></td>
			<td>
				<a href="view_yearly_purpose_cost.php?purpose=<?php echo $row['purpose']?>&year=<?php echo $year?>&submit=Search">
			<?php echo $row['purpose']?>
			</a>
			</td>
			<td><?php echo $row['name']?></td>
			<td><?php echo $row['description']?></td>
			
			<td><?php echo $row['date']?></td>
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
