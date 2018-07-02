<?php ob_start();?>
<?php require_once("includes/functions.php"); ?>
<?php
$thm=mysqli_query($connection,"select * from themes");
$thm=mysqli_fetch_assoc($thm);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Account Management System By YEARS Technology</title>
	<link rel="stylesheet" href="includes/style1.css" type="text/css" media="screen">
	<link rel="stylesheet" href="includes/print.css" type="text/css" media="print">
	<link rel="stylesheet" href="includes/jquery-ui.css">
</head>
<body>
	<div id="main">

	<div class="header">
		<div class="header-text"><img src="includes/img/banner.png" width="940" height="120"  alt=""/>

		</div>
    <div class="red-1"></div>
	<br>
	</div>
	<div id="main_body">
	
