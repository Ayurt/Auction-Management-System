<?php 
	require "../db_connect.php"; 
	require "../message_display.php";
	require "verify_member.php";
	require "header_member.php";
?>

<html>
	<head>
		<title>BIDS</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css" />
	</head>
	<body background ="img/R.jpg">
		<form class="cd-form" method="POST" action="#" enctype = "multipart/form-data">
			<center><legend>Add New Products Details</legend></center>
			
				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
				
				<div class="icon">
					<input class="b-isbn" id="b_isbn" type="number" name="b_isbn" placeholder="ISBN" required />
				</div>
				
				<div class="icon">
					<input class="b-title" type="text" name="b_title" placeholder="DESCRIPTION" required />
				</div>
				
				<div class="icon">
					<input class="b-contact" type="text" name="b_contact" placeholder="CONTACT NUMBER" required />
				</div>

				<div class="icon">
					<input class="b-category" type="text" name="b_price" placeholder="PRICE" required />
				</div>

				<div class="icon">
				<h2 >Upload Image : </h2>
					<input class="b-copies" type="file" name="b_copies" required />
				</div>
				
				<br />
				<input class="b-isbn" type="submit" name="b_add" value="Add product" />
		</form>
	<body>
	
	<?php
		if(isset($_POST['b_add']))
		{
			$query = $con->prepare("SELECT isbn FROM list WHERE isbn = ?;");
			$query->bind_param("s", $_POST['b_isbn']);
			$query->execute();
			
			if(mysqli_num_rows($query->get_result()) != 0)
				echo error_with_field("A Product with that ISBN already exists", "b_isbn");
			else
			{

				$file = $_FILES['b_copies'];
				$filename = $file['name'];
				$filepath = $file['tmp_name'];

				$destfile = '../img/'.$filename;

				$query = $con->prepare("INSERT INTO list VALUES(?, ?, ?, ?, ?, ?);");
				$query->bind_param("sssdsd", $_POST['b_isbn'], $_POST['b_title'], $_POST['b_contact'], $_POST['b_price'], $destfile, $_POST['b_price']);
				
				if(!$query->execute())
					die(error_without_field("ERROR: Couldn't add Product"));
				else
				move_uploaded_file($filepath, $destfile);
				echo success("New Product record has been added");
			}
		}
	?>
</html>