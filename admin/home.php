<?php
	require "../db_connect.php";
	require "verify_admin.php";
	require "header_admin.php";
?>

<html>
	<head>
		<title>BIDS</title>
		<link rel="stylesheet" type="text/css" href="css/home_style.css" />
	</head>
	<body  background ="img/auct.jpg">
		<div id="allTheThings">
			
			<a href="insert_product.php">
				<input type="button" value="Insert New Product Record" />
			</a><br />

			<a href="delete_products.php">
				<input type="button" value="Display and Delete the Records" />
			</a><br />

			<a href="pending_bids_requests.php">
				<input type="button" value="Manage Pending Bidding Requests" />
			</a><br />

			<a href="pending_registrations.php">
				<input type="button" value="Manage Pending Membership Registrations" />
			</a><br />

		</div>
	</body>
</html>