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
		<link rel="stylesheet" type="text/css" href="css/home_style.css">
	</head>
	<body background ="img/R.JPG">
		<?php
			$query = $con->prepare("SELECT * FROM list ORDER BY product");
			$query->execute();
			$result = $query->get_result();
			if(!$result)
				die("ERROR: Couldn't fetch Products");
			$rows = mysqli_num_rows($result);
			if($rows == 0)
				echo "<h2 align='center'>No Product Available</h2>";
			else
			{
				echo "<form class='cd-form' method='POST' action='#'>";
				echo "<center><legend>List of Available Product</legend></center>";
				echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
				echo "<table width='100%' cellpadding=10 cellspacing=10>";
				echo "<tr>
						<th></th>
						<th>Product Number<hr></th>
						<th>Product Description<hr></th>
						<th>PRICE<hr></th> 
						<th>IMAGE<hr></th>
					</tr>";
				for($i=0; $i<$rows; $i++)
				{
					$row = mysqli_fetch_array($result);
					echo "<tr>
							<td>
								<label class='control control--radio'>
									<input type='radio' name='rd_book' value=".$row[0]." />
								<div class='control__indicator'></div>
							</td>";
					 for($j=0; $j<5; $j++)
							if($j!=4)
							{
							if($j==2)
							continue;
							echo "<td>".$row[$j]."</td>";
							}else
							echo "<td>";?> <img src ="<?php echo $row['image'];?>" height= "100" width ="100"> <?php "</td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "<br /><input type='submit' name='m_request' value='Make your Bid' />";
				echo "<td>"; ?> <input type="text" name="bid" placeholder="Enter the Bid"/> <?php "</td>";
				echo "</form>";
			}
			if(isset($_POST['m_request']))
			{
				if(empty($_POST['rd_book']))
					echo error_without_field("Please select a product for bid issue");
				else
				{
					if(empty($_POST['bid']))
						echo error_without_field("please enter the bid");
					else{
				
					$name = $_SESSION['username'];
					$isb = $_POST['rd_book'];
					$bd =$_POST['bid'];
					$query = $con->prepare("SELECT product , max_bid  FROM list WHERE isbn = ?;");
					$query->bind_param("d", $isb);
					$query->execute();
					$resultRow = mysqli_fetch_array($query->get_result());
					$prdt = $resultRow[0];
					$mxbid = $resultRow[1];
					if($mxbid<$bd)
					{
						$query = $con->prepare("UPDATE `list` SET `max_bid` = '$bd' WHERE `list`.`isbn` = '$isb' ");
						$query->execute();
						

						$query = $con->prepare("SELECT * FROM pending_bids_requests WHERE book_isbn = ?;");
						$query->bind_param("s", $isb);
						$query->execute();
						$result = $query->get_result();
						$rows = mysqli_num_rows($result);
						if($rows==0)
						{
							$query = $con->prepare("INSERT INTO `pending_bids_requests` (`member`, `book_isbn`, `product`, `new_bid`) VALUES ('$name', '$isb','$prdt', '$bd');");
							if(!$query->execute())
							echo error_without_field("ERROR: Couldn\'t request bid");
							else
							echo success("Your bid has been recorded with highest bid. Soon you'll' be notified in Message if you will win this Bid");
						}
						else{
							$query = $con->prepare("UPDATE `pending_bids_requests` SET `member` = '$name' ,`new_bid` = '$bd' WHERE `book_isbn` = '$isb' ");// $con->prepare("UPDATE `BOOKS` set `max_bid` = '$bd' where `isbn` ='$isb' ");
							if(!$query->execute())
							echo error_without_field("ERROR: Couldn\'t request bid");
							else
							echo success("Your bid has been recorded with highest bid. Soon you'll' be notified in Message if you will win this Bid");
						}
					}
					else
					{
						echo error_without_field("ERROR: Your bid is less then the maximum bid of this product. Max bid is ".$mxbid);
					}
				}

				}
			}
		?>
	</body>
</html>

