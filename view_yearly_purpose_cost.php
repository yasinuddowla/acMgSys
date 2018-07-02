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
    <form action="view_yearly_purpose_cost.php" id="select_form" method="GET">
	<p align="center" style="color: #F00; font-size: 1.2em;">Show Yearly Purpose Based Costs</p>
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
       <label style="width:250px;float:left">Enter Year <span style="color: #F00; font-size: 1em;">* </span></label>:
	   <input type="number" name="year" required style="width:4em" tabindex="1" max="2100" min="2000" value="" placeholder="YYYY"/>

		<input type="submit" name="submit" id="simple_Search_btn" style="margin-left:100px" tabindex="1" value="Search" />
	</p>
  </form>
  </div>
  <br/>

<?php


$count=1;
?>
<?php
	if(isset($_GET['submit'])) { 
		$purpose=$_GET['purpose'];
		$year=$_GET['year'];
		$search_time=$year.'-';
		$query="select * from `cost` where date like '{$search_time}%' and purpose='{$purpose}'";
		$costs=mysqli_query($connection,$query);
		$num_rows=mysqli_num_rows($costs);
		if(!$num_rows){?>
			<div id="search_result">
				No Costs in <b><?php echo $purpose.' ['.$year.' ]'?></b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{

	
?>
<table class="all-table min-broad">
	<caption class="caption">Cost Information For <b><?php echo $purpose.' ['.$year.' ]'?></b></caption>
	<tr>
		<th width="15%">Sl. No</th>
		<th nowrap width="45%">Month</th>
		<th nowrap width="40%">Total Amount</th>
		
	</tr>
	<?php
	$total=0;
	for($i=7;;){
		$month=$i;
		if($month<10){
			$month='0'.$month;
		}
		$search_time=$year.'-'.$month.'-';
		$query="select * from `cost` where date like '{$search_time}%' and purpose='{$purpose}'";
		$costs=mysqli_query($connection,$query);
		$num_rows=mysqli_num_rows($costs);
		$monthly_total=0;
		while ($row=mysqli_fetch_assoc($costs)) {
			$monthly_total+=$row['amount'];
		}
	?>
		<tr>
			<td><?php echo $count++?></td>
			<td>
				<a href="view_monthly_purpose_cost.php?purpose=<?php echo $purpose?>&year=<?php echo $year?>&month=<?php echo intval($month)?>&submit=Search">
				<?php echo get_month_name($month)?>
			</a>

					
				
			</td>
			<td><?php echo $monthly_total?></td>
		</tr>
	<?php  
		if($i==12){
			$i=1;
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
		<td>Total</td>
		<td><?php echo $total?></td>
	</tr>
	<tr class="num-word">
		<td colspan="3"><?php echo num_to_words($total)?></td>
	</tr>
</table>
<br>
<br>
	<?php 
		}	
	}
	?>
<?php require_once("includes/footer.php"); ?>
