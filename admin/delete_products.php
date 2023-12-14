<?php
    require "../db_connect.php";
    require "../message_display.php";
	require "verify_admin.php";
	require "header_admin.php";
?>

<html>
	<head>
		<title>BIDS</title>
		<link rel="stylesheet" type="text/css" href="../member/css/home_style.css" />
        <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/home_style.css">
	</head>
	<body background ="img/blur.jpg">

    <?php
			$query = $con->prepare("SELECT * FROM list ORDER BY product");
			$query->execute();
			$result = $query->get_result();
			if(!$result)
				die("ERROR: Couldn't fetch Products");
			$rows = mysqli_num_rows($result);
			if($rows == 0)
				echo "<h2 align='center'>No product available</h2>";
			else
			{
				echo "<form class='cd-form'>";
				echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
				echo "<table width='100%' cellpadding=10 cellspacing=10>";
				echo "<tr>
						<th>ISBN<hr></th>
						<th>Product<hr></th>
						<th>Contact<hr></th>
						<th>Price<hr></th>
                        <th>Images<hr></th>
                        <th>Action<hr></th>
					</tr>";
				for($i=0; $i<$rows; $i++)
				{
					$row = mysqli_fetch_array($result);
				for($j=0; $j<5; $j++)
						if($j!=4)
            	        echo "<td>".$row[$j]."</td>";
						else
						echo "<td>";?> <img src ="<?php echo $row['image'];?>" height= "100" width ="100"> <?php "</td>";
						          
                   echo "<td><div class='text-center'><a href='dlt.php?id=".$row['isbn']."' style='color:#000000; text-decoration:none;'> Remove</a></div></td>";
				echo "</tr>";
				}
				echo "</table>";
				
				echo "</form>";
			}
			
			
		?>

    </body>

</html>