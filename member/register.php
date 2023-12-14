<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "../header.php";
?>

<html>
	<head>
		<title>LMS</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css">
	</head>
	<body background ="../admin/img/back.jpg">
		<form class="cd-form" method="POST" action="#">
			<center><legend>Member Registration</legend><p>Please fillup the form below:</p></center>
			
				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>

				<div class="icon">
					<input class="m-name" type="text" name="m_name" placeholder="Full Name" required />
				</div>

				<div class="icon">
					<input class="m-email" type="email" name="m_email" id="m_email" placeholder="Email" required />
				</div>
				
				<div class="icon">
					<input class="m-user" type="text" name="m_user" id="m_user" placeholder="Username" required />
				</div>
				
				<div class="icon">
					<input class="m-pass" type="password" name="m_pass" placeholder="Password" required />
				</div>
					
				<br />
				<input type="submit" name="m_register" value="Submit" />
		</form>
	</body>
	
	<?php
		if(isset($_POST['m_register']))
		{
			$query = $con->prepare("INSERT INTO pending_registrations(username, password, name, email) VALUES(?, ?, ?, ?);");
			$query->bind_param("ssss", $_POST['m_user'],($_POST['m_pass']), $_POST['m_name'], $_POST['m_email']);
			if($query->execute())
					echo success("Details submitted, soon you'll will be notified after verifications!");
			else
					echo error_without_field("Couldn\'t record details. Please try again later");		
		}
	?>
	
</html>