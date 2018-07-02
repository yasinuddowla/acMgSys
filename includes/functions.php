<?php
	ob_start();
	session_start();
	global $connection;
	global $db_name;
	global $root_url;
	$root_url = 'http://localhost/arabbd';
	$db_server="localhost";
	$db_user="root";
	$db_pass="yasin";
	$db_name="yearstech_arabbd_temp";
	$connection=mysqli_connect($db_server,$db_user,$db_pass,$db_name);
	if(mysqli_connect_errno())
	{
		die("DATABASE connection failed- ".mysqli_connect_error()."(".mysqli_connect_errno().")");
	}



	function redirect_to($new_location) {
	  header("Location: " . $new_location);
	  exit;
	}

	function set_utf8(){
		global $connection;
			mysqli_query($connection,'SET CHARACTER SET utf8');
			mysqli_query($connection,"SET SESSION collation_connection ='utf8_general_ci'");
	}
	
	function login($admin=0, $ac = 0){
		if($admin==1 && $ac==0){
			if(!isset($_COOKIE['admin'])){
				redirect_to('login.php');
			}
		}
		else if($admin==1 && $ac==1){
			if(!isset($_COOKIE['admin']) && !isset($_COOKIE['account'])){
				redirect_to('login.php');
			}
		}
	}
	/*function account_login(){
		if(!isset($_COOKIE['admin']) && !isset($_COOKIE['account'])){
			redirect_to('login.php');
		}
	}*/
	
?>
<?php
	function admin_nav(){
		global $root_url;
	?>
		<div id="admin_nav">
				<a class="admin_link"  href="<?php echo $root_url?>/">Home</a>
			
			
			
			<div class="dropdown">
				<span class="dropdown_btn" >Hajj</span>
				<div class="dropdown_menu">
					<a  href="<?php echo $root_url?>/add_hajji.php">
						Add a Hajji
					</a>
					<a  href="<?php echo $root_url?>/edit_hajji.php">
						Edit Hajji
					</a>

					<a   href="<?php echo $root_url?>/add_installment.php">
						Add Hajji Installment
					</a>
					<a   href="<?php echo $root_url?>/final_list.php">
						Final Hajji List
					</a>
					<a   href="<?php echo $root_url?>/flight_list.php">
						Flight List
					</a>
					<a   href="<?php echo $root_url?>/hajji_payment_details.php">
						Payment Details
					</a>
					<a   href="<?php echo $root_url?>/departure_card.php">
						Departure Card
					</a>
				</div>
			</div>
			<div class="dropdown">
				<span class="dropdown_btn" >View Cost</span>
				<div class="dropdown_menu">
					<a   href="<?php echo $root_url?>/view_daily_cost.php">
						Daily Costs
					</a>
				
					<a  href="<?php echo $root_url?>/view_monthly_cost.php">
						Monthly Costs
					</a>
					<a  href="<?php echo $root_url?>/view_yearly_cost.php">
						Yearly Costs
					</a>
				</div>
			</div>
			<div class="dropdown">
				<span class="dropdown_btn" >View Deposit</span>
				<div class="dropdown_menu">
					<a   href="<?php echo $root_url?>/view_daily_deposit.php">
						Daily Deposits
					</a>
				
					<a  href="<?php echo $root_url?>/view_monthly_deposit.php">
						Monthly Deposits
					</a>
					<a  href="<?php echo $root_url?>/view_yearly_deposit.php">
						Yearly Deposits
					</a>
									</div>
			</div>
			<div class="dropdown">
				<span class="dropdown_btn" >Reports</span>
				<div class="dropdown_menu">
					<a  href="<?php echo $root_url?>/view_daily_bank_transaction.php">
						Daily Transactions
					</a>
					<a  href="<?php echo $root_url?>/view_monthly_bank_transaction.php">
						Monthly Transactions
					</a>
					<a  href="<?php echo $root_url?>/view_yearly_bank_transaction.php">
						Yearly Transactions
					</a>

				</div>
			</div>
			
			<div class="dropdown"> 
				<span class="dropdown_btn" >Accessories</span>
				<div class="dropdown_menu">
				<a  href="<?php echo $root_url?>/purpose/">Add Purpose</a>
				<a  href="<?php echo $root_url?>/account/">Add Account</a>
				
				<a  href="<?php echo $root_url?>/view_monthly_temp.php">Monthly Temporary</a>
				<a  href="<?php echo $root_url?>/view_monthly_transfer.php">View Monthly Transfers</a>
				</div>
			</div>

			<div class="dropdown"> 
				<span class="dropdown_btn" >Party</span>
				<div class="dropdown_menu">
				<a  href="<?php echo $root_url?>/party/addparty.php">Add Party</a>
				<a  href="<?php echo $root_url?>/party/party_bill.php">Party Bill</a>
				<a  href="<?php echo $root_url?>/party/preview_bills.php">Show Bills</a>
			
				</div>
			</div>
			<div class="dropdown">
				<span class="dropdown_btn" >Voucher</span>
				<div class="dropdown_menu">
					<a  href="<?php echo $root_url?>/cost.php">
						Cost Voucher
					</a>

					<a   href="<?php echo $root_url?>/deposit.php">
						Deposit Voucher
					</a>
					<a   href="<?php echo $root_url?>/temp_voucher.php">
						Temporary Voucher
					</a>
					<a  href="<?php echo $root_url?>/transfer.php">
						Transfer
					</a>
			
					<?php
						if(isset($_COOKIE['admin'])){
					?>
					<a   href="<?php echo $root_url?>/edit_voucher.php">
						Edit Voucher
					</a>

					<?php	}
					?>
					
			
					<a   href="<?php echo $root_url?>/preview_voucher.php">
						Show Voucher
					</a>
				</div>
			</div>
			<div class="dropdown">
				<span class="dropdown_btn" >Settings</span>
				<div class="dropdown_menu">

					<a href="<?php echo $root_url?>/change_password.php">Change Password
					</a>
					<a  href="<?php echo $root_url?>/logout.php">Log Out[<?php echo $_COOKIE["username"]?>]
					
					</a>
					<!--<a href="<?php echo $root_url?>/change_theme.php">Change Style</a>-->
				</div>
			</div>

			

		</div>
	<br>
	<p class="btn-container">
	<button class="btn" onclick="location.href = '<?php echo $root_url?>/acinfo.php'">Account Overview</button>
	

	</p>
	<?php }
?>
<?php

	function get_print_header(){
		return '<div class="print-header">
			<div class="h1">ARAB BANGLADESH OVERSEAS & HAJJ GROUP</div>
			<div class="add">
				186/200, KHWAJA MARKET (3DR   FLOOR), C.D.A AVENUE, MURADPUR, PANCHLAISH 
			CHITTAGONG. PHONE: 2557210, MOBILE: 01816440555, 01911579431
			</div>
		<br>
		</div>';
	}
	function getaccount($id){
			global $connection;
			$a=mysqli_query($connection,"SELECT  * FROM `accounts` where account_id='{$id}'");
			$r=mysqli_fetch_assoc($a);
			
			return $r;
		}
	function getPurpose($id){
		global $connection;
		$a=mysqli_query($connection,"SELECT  * FROM `purposes` where id='{$id}'");
		$r=mysqli_fetch_assoc($a);
		return $r;
	}
	function getPartner($id){
		global $connection;
		$a=mysqli_query($connection,"SELECT  * FROM `partner` where partner_id='{$id}'");
		$r=mysqli_fetch_assoc($a);
		return $r;
	}
function getParty($id){
		global $connection;
		$a=mysqli_query($connection,"SELECT  * FROM `party` where party_id='{$id}'");
		$r=mysqli_fetch_assoc($a);
		return $r;
	}


	function get_month_name($month){
		if($month=='01')
			return 'January';
		else if($month=='02')
			return 'February';
		else if($month=='03')
			return 'March';
		else if($month=='04')
			return 'April';
		else if($month=='05')
			return 'May';
		else if($month=='06')
			return 'June';
		else if($month=='07')
			return 'July';
		else if($month=='08')
			return 'August';
		else if($month=='09')
			return 'September';
		else if($month=='10')
			return 'October';
		else if($month=='11')
			return 'November';
		else if($month=='12')
			return 'December';
	}
function get_no_days( $year, $month) {
	if( $month==1)
		return 31;
	else if( $month==2){
		if( $year%4==0){
			if($year%100==0){
				if($year%400==0){
					return 29;
				}
				else{
					return 28;
				}
			}
			else{
				return 29;
			}

		}
		else{
			return 28;
		}
	}
	else if( $month==3)
		return 31;
	else if($month==4)
		return 30;
	else if( $month==5)
		return 31;
	else if( $month==6)
		return 30;
	else if( $month==7)
		return 31;
	else if( $month==8)
		return 31;
	else if( $month==9)
		return 30;
	else if( $month==10)
		return 31;
	else if( $month==11)
		return 30;
	else if( $month==12)
		return 31;
}
?>
<?php
//Formating Date
function format_date($date){
	$m=$date[5].$date[6];
	$d=$date[8].$date[9];
	$y=$date[0].$date[1].$date[2].$date[3];
	return $d.'-'.getshortmonth($m).'-'.$y;
}

function getshortmonth($month){
		if($month=='01')
			return 'Jan';
		else if($month=='02')
			return 'Feb';
		else if($month=='03')
			return 'Mar';
		else if($month=='04')
			return 'Apr';
		else if($month=='05')
			return 'May';
		else if($month=='06')
			return 'Jun';
		else if($month=='07')
			return 'Jul';
		else if($month=='08')
			return 'Aug';
		else if($month=='09')
			return 'Sep';
		else if($month=='10')
			return 'Oct';
		else if($month=='11')
			return 'Nov';
		else if($month=='12')
			return 'Dec';
}

//Comma Separated Number
function get_csn($num){
	$num=(string)$num;
	$num=strrev($num);
	$csn='';
	for($i=0;$i<strlen($num);$i++){
		if($num[$i]=='-'){
			$csn.=$num[$i];
			continue;
		}
		if($i==3){
			$csn.=',';
			
			$csn.=$num[$i];
		}
		else if($i>3 && ($i+1)%2==0){
			$csn.=',';
			$csn.=$num[$i];
		}
		else{
			$csn.=$num[$i];
		}
	}
	return strrev($csn);
}
	

function num_to_words($num){
	$word='';
	if($num==0){
		$word='zero';
	}
	else{
		if(intval($num/10000000)!=0){
			$word.=get_basic_num_to_words(intval($num/10000000)).' Core ';
			$num%=10000000;
		}
		if(intval($num/100000)!=0){
			$word.=get_basic_num_to_words(intval($num/100000)).' Lakh ';
			$num%=100000;
		}
		if(intval($num/1000)!=0){
			$word.=get_basic_num_to_words(intval($num/1000)).' Thousand ';
			$num%=1000;
		}
		if(intval($num/100)!=0){
			$word.=get_basic_num_to_words(intval($num/100)).' Hundred ';
			$num%=100;
		}
		if($num!=0){
			
			$word.=get_basic_num_to_words($num);
		}
	}
	return ucwords($word);
}
function get_basic_num_to_words($num){
	if($num<=20)
		return num_2_str_ltd($num);
	else{
		if($num<30)
			return 'twenty '.num_2_str_ltd($num%10);
		else if($num==30)
			return 'thirty';
		else if($num<40)
			return 'thirty '.num_2_str_ltd($num%10);
		else if($num==40)
			return 'forty';
		else if($num<50)
			return 'forty '.num_2_str_ltd($num%10);
		else if($num==50)
			return 'fifty';
		else if($num<60)
			return 'fifty '.num_2_str_ltd($num%10);
		else if($num==60)
			return 'sixty';
		else if($num<70)
			return 'sixty '.num_2_str_ltd($num%10);
		else if($num==70)
			return 'seventy';
		else if($num<80)
			return 'seventy '.num_2_str_ltd($num%10);
		else if($num==80)
			return 'eighty';
		else if($num<90)
			return 'eighty '.num_2_str_ltd($num%10);
		else if($num==90)
			return 'ninety';
		else if($num<100)
			return 'ninety '.num_2_str_ltd($num%10);
		else 
			return $num;
	}
}
function num_2_str_ltd($num){
	if($num==0)
		return 'zero';
	else if($num==1)
        return 'one';
	else if($num==2)
        return 'two';
	else if($num==3)
        return 'three';
	else if($num==4)
        return 'four';
	else if($num==5)
        return 'five';
	else if($num==6)
        return 'six';
	else if($num==7)
        return 'seven';
	else if($num==8)
        return 'eight';
	else if($num==9)
        return 'nine';
	else if($num==10)
        return 'ten';
	else if($num==11)
        return 'eleven';
	else if($num==12)
        return 'twelve';
	else if($num==13)
        return 'thirteen';
	else if($num==14)
        return 'fourteen';
	else if($num==15)
        return 'fifteen';
	else if($num==16)
        return 'sixteen';
	else if($num==17)
        return 'seventeen';
	else if($num==18)
        return 'eighteen';
	else if($num==19)
        return 'nineteen';
	else if($num==20)
        return 'twenty';
}
?>