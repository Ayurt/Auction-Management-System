<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/header_member_style.css" />
	</head>
	<body>
		<header>
			<div id="cd-logo">
				<a href="../">
					<img src="img/ic_logo2.svg" alt="Logo" width="45" height="45" />
					<p>BIDDING SYSTEM</p>
				</a>
			</div>
			
			<div class="dropdown">
				<button class="dropbtn">
					<p id="librarian-name"><?php echo $_SESSION['username'] ?></p>
				</button>
				<div class="dropdown-content">
					<a href="add_prdt.php">ADD New Products</a>
					<a href="bids_message.php">My Messages</a>
					<a href="../logout.php">Logout</a>
				</div>
			</div>
		</header>
	</body>
</html>