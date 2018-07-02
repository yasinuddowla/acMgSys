<?php require_once("includes/functions.php"); ?>
<?php
	login(1,1);
?>
<?php
$id=$_GET['q'];
$accounts=mysqli_query($connection,"SELECT  * FROM `accounts` where id='{$id}' ");
$r=mysqli_fetch_assoc($accounts);
echo $r['balance'];

?>
