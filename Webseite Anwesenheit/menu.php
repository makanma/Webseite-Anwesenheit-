<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	$db_handle  = pg_connect("host=10.8.0.129 dbname=postgres user=Denis password=Start1234");
	
    $id_mitarbeiter="";

	 if (isset($_POST["action"]) || $_SERVER["REQUEST_METHOD"] == "POST")
	 {
		
		if($_POST['action'] == "update"){

			$query="Update mitarbeiter set vorname='" . $_POST['firstName']."', nachname='".$_POST['lastName']."' where id_mitarbeiter=".$_POST['id'].";";
			pg_exec($db_handle, $query );
			
		}
		
		if($_POST['action'] == "create")
		{

			$query="select i_id, rtxt_vorname, rtxt_nachname from f_create_new('" . $_POST['vorname']."', '".$_POST['nachname']."')";
			
			$result = pg_query($db_handle, $query );
			while ($row = pg_fetch_assoc($result)) 
			{
				echo "<li id='list.".$row['i_id']."' personId=".$row['i_id']." value=".$row['i_id']."&".$row['rtxt_vorname']."&".$row['rtxt_nachname'].">".$row['rtxt_vorname']." ".$row['rtxt_nachname']."</li>";				
			}
			exit();
		}

		if($_POST['action'] == "delete")
		{
			
			
			$query = "select f_delete('".$_POST['id']."');";
			pg_exec($db_handle, $query );

		}


	
		//get last id
		if(isset($_POST['lastID'])){
			$aa =$_POST;
			$query="select id_mitarbeiter from mitarbeiter order by id_mitarbeiter desc limit 1";
			$result = pg_exec($db_handle, $query );
			$id = pg_result($result, 0, 'id_mitarbeiter');
			echo $id;
			
			
		}

		exit();
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
	<style>
		ul
		{
			list-style-type : none;
			margin: 0;
			padding: 0;
		}

		li
		{
			cursor: pointer;
			text-align: center;
		}

		li:hover
		{
			text-decoration: underline;
		}
	</style>
  </head>
  <body>
    <?php
        $getMitarbeiter = pg_exec($db_handle, "SELECT id_mitarbeiter,vorname,nachname FROM mitarbeiter order by id_mitarbeiter");
		echo "<header><h1 contenteditable>Personen Bearbeiten</h1>
			<a href='index.php' ><button id='BackButton'>Zurück</button></a>
			</header>";
		
		echo "<main>";	
			
				echo "<div class='container__sidebar'>";
					
					
					echo "<ul id='personenlist' name='names' style='height: 80%'>";
					for ($row = 0; $row < pg_numrows($getMitarbeiter); $row++) {
						$id = pg_result($getMitarbeiter, $row, 'id_mitarbeiter');
						$vorname = pg_result($getMitarbeiter, $row, 'vorname');
						$nachname = pg_result($getMitarbeiter, $row, 'nachname');
						
						echo "<li id='list.$id' personId=$id value=".$id."&".$vorname."&".$nachname.">".$vorname." ".$nachname."</li>";
						
					}
					echo "</ul>";
				echo "</div>";
			//middle
			echo "<div class='container__main'>";
				echo "<div class='container1'>";
					echo "<div class='container_fnameInput'>";
						echo "<input type='hidden' id='nameID'></input>";
						echo "<label class='container__label1'>Vorname</label>";
						echo "<input type='text' name='fname' id='vnameInput'>";
						
					echo "</div>";
					echo "<div class='container_nnameInput'>";	
						echo "<label class='container__label2'>Nachname</label>";
						echo "<input type='text' name='nname' id='nnameInput'>";
						
					echo "</div>";
				echo "</div>";
				echo "<div class='button_group'>";
					echo "<input type='button' name='update' value='update' id='updateButton' >";
					echo "<input type='button' name='remove' value='remove entry' id='removeEntry' >";
				echo "</div>";
			echo "</div>"; // container__main
			// create new entry
			echo "<div class='container__right'>";
				echo "<div class='container1'>";
				echo "<div class='container_fnameInput'>";
					echo "<label class='container__label1'>Vorname</label>";
					echo "<input type='text' name='fname' id='firstNameCreate'>";
					
				echo "</div>";
				echo "<div class='container_nnameInput'>";	
					echo "<label class='container__label2'>Nachname</label>";
					echo "<input type='text' name='nname' id='lastnameCreate'>";
					
				echo "</div>";
					echo "<input type='button' id='createbutton' name='create' value='create new entry'>";
				echo "</div>";
			//echo "</form>";
			echo "</div>";
			echo "</div>";
		echo "</main>";









    ?>