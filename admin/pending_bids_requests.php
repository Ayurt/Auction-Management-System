<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_admin.php";
	require "header_admin.php";
?>

<html>
	<head>
		<title>BIDS</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/custom_checkbox_style.css">
		<link rel="stylesheet" type="text/css" href="css/pending_book_requests_style.css">
	</head>
	<body  background ="../member/img/R.jpg">
		<?php
			$query = $con->prepare("SELECT * FROM pending_bids_requests;");
			$query->execute();
			$result = $query->get_result();;
			$rows = mysqli_num_rows($result);
			if($rows == 0)
				echo "<h2 align='center'>No requests pending</h2>";
			else
			{
				echo "<form class='cd-form' method='POST' action='#'>";
				echo "<center><legend>Pending bid requests</legend></center>";
				echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
				echo "<table width='100%' cellpadding=10 cellspacing=10>
						<tr>
							<th></th>
							<th>Username<hr></th>
							<th>ISBN<hr></th>
							<th>Product<hr></th>
							<th>BID PRICE<hr></th>
						</tr>";
				for($i=0; $i<$rows; $i++)
				{
					$row = mysqli_fetch_array($result);
					echo "<tr>";
					echo "<td>
							<label class='control control--checkbox'>
								<input type='checkbox' name='cb_".$i."' value='".$row[0]."' />
								<div class='control__indicator'></div>
							</label>
						</td>";
					for($j=1; $j<5; $j++)
						echo "<td>".$row[$j]."</td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "<br /><br /><div style='float: right;'>";
				echo "<input type='submit' value='Reject Request' name='l_reject' />&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "<input type='submit' value='Allow' name='l_grant'/>";
				echo "</div>";
				echo "</form>";
			}
			
			
			if(isset($_POST['l_grant']))
			{
				$requests = 0;
				for($i=0; $i<$rows; $i++)
				{
					if(isset($_POST['cb_'.$i]))
					{
						$request_id =  $_POST['cb_'.$i];
						$query = $con->prepare("SELECT member, book_isbn, new_bid, product FROM pending_bids_requests WHERE request_id = ?;");
						$query->bind_param("d", $request_id);
						$query->execute();
						$resultRow = mysqli_fetch_array($query->get_result());
						$member = $resultRow[0]; 
						$isbn = $resultRow[1];
						$nbd = $resultRow[2];
						$prdt = $resultRow[3];
						$msg = "you won this bid";
						$sql = "INSERT INTO `bids_messages` ( `member`, `book_isbn`, `your_bid` , `messag`) VALUES ( '$member', '$isbn', '$nbd', '$msg')";
						if(!$con->query($sql))
							die(error_without_field("ERROR: Couldn\'t issue bid"));
						
						$query = $con->prepare("SELECT contact FROM list WHERE isbn = ?;");
						$query->bind_param("s", $isbn);
						$query->execute();
						$res = mysqli_fetch_array($query->get_result());
						$num = $res[0];
						$sql = "INSERT INTO `history` ( `isbn`, `product`, `contact` ) VALUES ( '$isbn', '$prdt', '$num')";
		
						if(!$con->query($sql))
							die(error_without_field("ERROR: Couldn\'t add it in history"));

						$query = $con->prepare("DELETE FROM list WHERE isbn = ?");
						$query->bind_param("d", $isbn);
						if(!$query->execute())
							die(error_without_field("ERROR: Couldn\'t delete product"));
					
						$query = $con->prepare("DELETE FROM pending_bids_requests WHERE request_id = ?");
						$query->bind_param("d", $request_id);
						if(!$query->execute())
							die(error_without_field("ERROR: Couldn\'t delete values"));
						$requests++;		
					}
				}
				if($requests > 0)
					echo success("Granted Successfully!".$requests." requests");
				else
					echo error_without_field("No request selected");
			}
			
			if(isset($_POST['l_reject']))
			{
				$requests = 0;
				for($i=0; $i<$rows; $i++)
				{
					if(isset($_POST['cb_'.$i]))
					{
						$requests++;
						$request_id =  $_POST['cb_'.$i];
						
						$query = $con->prepare("SELECT member , book_isbn , new_bid, product FROM pending_bids_requests WHERE request_id = ?;");
						$query->bind_param("d", $request_id);
						$query->execute();
						$resultRow = mysqli_fetch_array($query->get_result());
						$member = $resultRow[0];
						$isbn = $resultRow[1];
						$nbd = $resultRow[2];
						$prdt = $resultRow[3];
						$msg = "you dint get this bid, admin rejected because of other bid or any other reasons";
						
						$sql = "INSERT INTO `bids_messages` ( `member`, `book_isbn`, `your_bid` , `messag`) VALUES ( '$member', '$isbn', '$nbd' , '$msg')";
						$con->query($sql);

						$query = $con->prepare("SELECT contact FROM list WHERE isbn = ?;");
						$query->bind_param("s", $isbn);
						$query->execute();
						$resultRow = mysqli_fetch_array($query->get_result());
						$num = $resultRow[0];
						$sql = "INSERT INTO `history` ( `isbn`, `product`, `contact` ) VALUES ( '$isbn', '$prdt', '$num')";
						if(!$con->query($sql))
							die(error_without_field("ERROR: Couldn\'t add it in history"));


						$query = $con->prepare("DELETE FROM list WHERE isbn = ?");
						$query->bind_param("d", $isbn);
						if(!$query->execute())
							die(error_without_field("ERROR: Couldn\'t delete product"));


						$query = $con->prepare("DELETE FROM pending_bids_requests WHERE request_id = ?");
						$query->bind_param("d", $request_id);
						if(!$query->execute())
							die(error_without_field("ERROR: Couldn\'t delete values"));
						}
				}
				if($requests > 0)
					echo success("Successfully deleted ".$requests." requests");
				else
					echo error_without_field("No request selected");
			}
		?>
	</body>
</html>