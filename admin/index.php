<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "../verify_logged_out.php";
	require "../header.php";
?>

<html>
	<head>
		<title>BIDS</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css">
	</head>
	<body background ="img/back.jpg">
		<form class="cd-form" method="POST" action="#">
		
		<center><legend>Admin Login</legend></center>

			<div class="error-message" id="error-message">
				<p id="error"></p>
			</div>
			
			<div class="icon">
				<input class="l-user" type="text" name="l_user" placeholder="Username" required />
			</div>
			
			<div class="icon">
				<input class="l-pass" type="password" name="l_pass" placeholder="Password" required />
			</div>
			
			<input type="submit" value="Login" name="l_login"/>

			
			
		</form>
		<p align="center"><a href="../index.php" style="text-decoration:none;">Go Back</a>
	</body>
	
	<?php
		if(isset($_POST['l_login']))
		{
			$query = $con->prepare("SELECT id FROM addmin WHERE username = ? AND password = ?;");
			$query->bind_param("ss", $_POST['l_user'],($_POST['l_pass']));
			$query->execute();
			if(mysqli_num_rows($query->get_result()) != 1)
				echo error_without_field("Invalid username/password combination");
			else
			{
				$_SESSION['type'] = "admin";
				$_SESSION['id'] = mysqli_fetch_array($result)[0];
				$_SESSION['username'] = $_POST['l_user'];
				header('Location: home.php');
			}
		}
	?>
	
</html>