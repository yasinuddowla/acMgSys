<?php require_once("includes/header.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
	if(!isset($_COOKIE['loggedin'])){
		redirect_to('login.php');
	}
?>

 <?php
admin_nav();
	
 ?> 
	<div class="io-form" id="form_area">
    <form action="hajji_payment_details.php" id="select_form" method="GET">
	<p align="center" style="color: #F00; font-size: 1.2em;">Show Flight List</p>
	<p align="center">
	    <label >Enter Refence<span style="color: #F00; font-size: 12px;">* </span></label>:
	    <input type="text" name="ref"  placeholder="Reference">
	</p>
	<p align="center">
       <label >Enter Year<span style="color: #F00; font-size: 12px;">* </span></label>:
	   <input type="number" required name="year" tabindex="1" max="2100" min="2000" value="" placeholder="YYYY"/>
		<input type="submit" name="submit" id="simple_Search_btn" tabindex="1" value="Search" />
	</p>
  </form>
  </div>

<?php


$count=1;
?>
<?php
	if(isset($_GET['submit'])) { 
	
		$year=$_GET['year'];
		$Search_time=$year.'-';
		$ref=$_GET['ref'];
		if($ref == ''){
		    $query="select * from `hajji_info` where date like '{$Search_time}%'";
		}
		else{
		    $query="select * from `hajji_info` where date like '{$Search_time}%' and reference = '{$ref}'";
		}
	
		
		$persons=mysqli_query($connection,$query);
		$num_rows=mysqli_num_rows($persons);
		if(!$num_rows){?>
			<div id="search_result">
				No Payments in <b><?php echo $year?></b>
			</div>
			<br>
			<br>
		<?php 
		}
		else{

	
?>
		<div class="print-header">
			<div class="h1">ARAB BANGLADESH OVERSEAS & HAJJ GROUP</div>
			<div class="add">
				186/200, KHWAJA MARKET (3DR   FLOOR), C.D.A AVENUE, MURADPOOR, PANCHLAISH 
				CHITTAGONG. PHONE: 2557210, MOBILE: 01816440555, 01911579431
			</div>
			<br>
		</div>


<?php 
	$table_heading='
		<caption class="flight-list-cptn caption">Yearly Payment List <b> [ '. $year.' ]</b></caption>
	<tr>
		<th width="4%">SL</th>
		<th width="10%">ID NO.</th>
		<th width="20%">Name</th>
		<th width="10%">Reference</th>
		<th width="12%">Total</th>
		<th width="12%">Payment Received</th>
		<th width="12%">Payment Due</th>
		
		<th width="8%">Mobile NO</th>
		
	</tr>';
	
	
		$pages=ceil($num_rows/25);
		$gross = 0;
		$total_paid = 0;
		$total_due = 0;
		$page=1;
      while($num_rows!=0){
			$table_out='<table class="all-table full-broad flight-list">';
			if($num_rows>25){
				$rows=25;
				$num_rows-=25;
			}
			else{
				$rows=$num_rows;
				$num_rows=0;
			}
			if($rows<25 || ($rows==25 && $num_rows==0)){
					echo '<div class="no-break">';
			}
			else{
				echo '<div class="flight-list-div">';
			}
			echo $table_out;
			echo $table_heading;

			for($i=1;$i<=$rows;$i++){
				$sumTotal=0;
				$sumPaid=0;
				$sumDue=0;
				$row=mysqli_fetch_assoc($persons);

				$paid=$row['installment_1']+$row['installment_2']+$row['installment_3'];
				
				$total=$row['total_amount'];

				$due=$total-$paid;
				
				$sumTotal+=$total;
				$sumDue+=$due;
				$sumPaid+=$paid;

				$gross=$row['total_amount'];
				$total_paid +=$paid;
				$total_due += $total-$paid;
	?>
				<tr>
					<td><?php echo $count++?></td>
					<td><a href="add_installment.php?search_id=<?php echo strtoupper($row['hajji_id'])?>"><?php echo strtoupper($row['hajji_id'])?></a></td>
					<td><?php echo strtoupper($row['name'])?></td>
					<td><?php echo strtoupper($row['reference'])?></td>
					<td><?php echo $total?></td>
					<td><?php echo $paid?></td>
					<td><?php echo $due?></td>
					<td><?php echo strtoupper($row['per_phone'])?></td>
				</tr>
				<?php  
			}
	

		
		
		echo '</table><br>';

	  	echo '<div class="years-cpt" align="center"><b>www.yearstech.com</b></div>';
		echo '<p class="page-no">'.$page++.' of '.$pages.'</p>';
		echo '</div><br><br><br>';
	  }
	  ?>

<div style="width: 60%;margin: 0px auto">
<table style="width: 80%;margin: 0px auto">
	<tr>
		<th>Total</th>
		<td><?php echo $gross?></td>
	</tr>
	<tr>
		<th>Paid</th>
		<td><?php echo $total_paid?></td>
	</tr>
	<tr>
		<th>Due</th>
		<td><?php echo $total_due?></td>
	</tr>
</table>

</div>
<p  align="center" style="width:80%;margin:0px auto">
			<input type="button" id="print" title="Print" onClick="window.print()" value="Print">
</p>
	<?php 
		}	
	}
	?>
<?php require_once("includes/footer.php"); ?>

