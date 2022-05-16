<?php
	

	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	$db_handle  = pg_connect("host=10.8.0.129 dbname=postgres user=Denis password=Start1234");
	
	if ($db_handle) {

		//echo 'Connection attempt succeeded.';

	} else {
		//echo 'Connection attempt failed.';
	}

	//echo '<pre>'.print_r($_POST,true).'</pre>';
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkbox']))
	{
		// collect value of input field
		$name = $_POST['checkbox'];
		if (empty($name)) {
			;
		} else {
			
			$param = preg_split ( "/[&]/", $name);
			
			pg_exec($db_handle, "SELECT switchAnwesenheit('".$param[1]."','".$param[0]."');");
			//echo $name;

			exit();
		}
	}



?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>title</title>
    <link rel="stylesheet" href="style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
	<script src="script.js"></script>
  </head>
  <body>
    <?php
 
		//echo "<h3>Connection Information</h3>";
		
		//echo "<h3>Checking the query status</h3>";
		$query = "SELECT id_mitarbeiter,vorname,nachname FROM mitarbeiter";
		//var_dump($db_handle);
		$aa = pg_exec($db_handle, "Select generateDate();");
		$getMitarbeiter = pg_exec($db_handle, $query);
		$getDates = pg_exec($db_handle, "SELECT datum from tage");
		
		$num_cols = pg_num_fields($getMitarbeiter);
		$num_rows = pg_num_rows($getMitarbeiter);
	if ($getMitarbeiter)
	{
		//echo "The query executed successfully.<br>";
		echo "<div id='content'>";
		//form
		//echo "<form method='post' id='myForm'>";
		echo "<table id='table1'>\n";
		echo "<tr id='col'>\n";
		echo "<th>Datum</th>";
		for ($row = 0; $row < pg_numrows($getMitarbeiter); $row++) {
			$vorname = pg_result($getMitarbeiter, $row, 'vorname');
			$nachname = pg_result($getMitarbeiter, $row, 'nachname');
			
			echo "<th class='names'>".$vorname." ".$nachname;
		
			echo "</th>";
			
		}
		
		echo "</tr>\n";
			//table rows
		$dateNum= pg_numrows($getDates);
		for ($row = 0; $row < $dateNum; $row++) {
			echo "<tr id='col'>\n";
			
			$date = pg_result($getDates, $row, 'datum');
			
			echo "<td id='dateTd'><script>document.write(formatDate('".$date."'));</script></td>";
			//echo "<td id='dateTd'><script>document.getElementById('dateTd').innerHTML = formatDate(".$date.");</script></td>";
			//columns
			for ($row2 = 0; $row2 < pg_numrows($getMitarbeiter); $row2++) {
				$id = pg_result($getMitarbeiter, $row2, 'id_mitarbeiter');
				$anwesenheit = pg_query($db_handle, "select getAnwesenheit('".(string)strval($date)."','".$id."')");
				$isAnwesend = $rowsAnwesend = pg_fetch_all ( $anwesenheit);
				
				$anwesend = $isAnwesend[0]["getanwesenheit"];
				
				$check="unchecked";
				
				if ($anwesend=='t') {
					$check="checked";
				}
				//onclick=handleClick(this);
				?>
				<form method='post'>
				
				<td><input type="checkbox" name='checkbox' <?php echo "value='".$id."&".$date."' ".$check;?> ></td>;
				
				</form>
				<?php
			}
			
			echo "</tr>\n";
		}
	//echo "</form>";
	//form
	}
	

	echo "</div>";
	
	echo "<div id='menu'>";
	echo " <a href='menu.php' ><button type='button'>CRUD Namen</button> </a>";
	echo "</div>";	


?>
	
	
  </body>
</html>