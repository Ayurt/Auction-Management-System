<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_member.php";
	require "header_member.php";
?>

<html>
	<head>
		<title>BIDS</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="css/my_books_style.css">
	</head>
	<body background ="img/R.jpg">
	
		<?php
			$nam = $_SESSION['username'];
			$query1 = mysqli_query($con, "SELECT * FROM bids_messages WHERE member = '$nam';");
		
			$rows = mysqli_num_rows($query1);

			if($rows == 0)
				echo "<h2 align='center'>There is No Updates Yet!</h2>";
			else
			{
				echo "<form class='cd-form' method='POST' action='#'>";
				echo "<center><legend>My MESSAGES</legend></center>";
				echo "<div class='success-message' id='success-message'>
						<p id='success'></p>
					</div>";
				echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
				echo"<table width='100%' cellpadding='10' cellspacing='10'>
						<tr>
							<th></th>
							<th>ISBN<hr></th>
							<th>Product<hr></th>
							<th>contact<hr></th>
							<th>Your Bid<hr></th>
							<th>Message<hr></th>
						</tr>";
					$i=0;
					while($isbnt = mysqli_fetch_array($query1))
					{ 
					
					$isbn = $isbnt[2];
						$query = $con->prepare("SELECT product, contact FROM history WHERE isbn = ?;");
						$query->bind_param("s", $isbn);
						$result = $query->execute();
						$innerRow = mysqli_fetch_array($query->get_result());
						
						echo "<tr>
								<td>
									<label class='control control--checkbox'>
										<input type='checkbox' name='cb_book".$i."' value='".$isbn."'>
										<div class='control__indicator'></div>
									</label>
								</td>";
						echo "<td>".$isbn."</td>";
						for($j=0; $j<2; $j++)
							echo "<td>".$innerRow[$j]."</td>";
						echo "<td>".$isbnt[3]."</td>";
						echo "<td>".$isbnt[4]."</td>";
						echo "</tr>";
						$i++;
			}
				echo "</table><br />";
				echo "<input type='submit' name='b_return' value='CLAIM or DELETE' />";
				echo "</form>";
			}
			
			if(isset($_POST['b_return']))
			{
				$claim = 0;
				for($i=0; $i<$rows; $i++)
					if(isset($_POST['cb_book'.$i]))
					{
						$query = $con->prepare("DELETE FROM bids_messages WHERE member = ? AND book_isbn = ?;");
						$query->bind_param("ss", $_SESSION['username'], $_POST['cb_book'.$i]);
						if(!$query->execute())
							die(error_without_field("ERROR: Couldn\'t return the books"));
						
						$claim++;
					}
				if($claim > 0)
				{
					echo '<script>
							document.getElementById("success").innerHTML = "Successfully claimed/deleted '.$claim.' item";
							document.getElementById("success-message").style.display = "block";
						</script>';
				}
				else
					echo error_without_field("Please select a checkbox to claim");
			}
		?>
		
	</body>
</html>