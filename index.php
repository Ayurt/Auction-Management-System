<?php
	require "db_connect.php";
	session_start();
	
	if(empty($_SESSION['type']));
	else if(strcmp($_SESSION['type'], "admin") == 0)
		header("Location: admin/home.php");
	else if(strcmp($_SESSION['type'], "member") == 0)
		header("Location: member/home.php");
?>

<html>
	<head>
		<title>BIDS</title>
		<link rel="stylesheet" type="text/css" href="css/index_style.css" />
	</head>
	<body background ="img/back.png">
		<div id="allTheThings">
			<div id="member">
				<a href="member">
				<hr>
				<b>BID HERE</b>
				<hr>
				</a>
				<a href="member">
				<b>SELL HERE</b>
				<hr>
				</a>
				<a href="member/register.php">
				<b>REGISTER</b>
				<hr>
				</a>
				<a href="admin">
				<b>ADMIN</b>
				<hr>
				</a>
			</div>
		</div>
	</body>
</html>