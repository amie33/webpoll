<!DOCTYPE html> 
<html lang="en"> 
<head> 
	<title> Web Poll</title> 
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link href='https://fonts.googleapis.com/css?family=Barriecito' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Bungee+Shade' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Butcherman' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Londrina+Shadow' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Mountains+of+Christmas' rel='stylesheet'>
</head> 
<body>
<?php 
	//connect to the database either locally or....
		if($_SERVER['HTTP_HOST'] == "localhost")
		{
			define("HOST", "localhost"); 
			define("USER", "root");
			define("PASS", "sparky33");
			define("BASE", "webpoll");
		}
	//connect live 
		else{
			define("HOST", "localhost");
			define("USER", "id10733680_poll"); 
			define("PASS", "polly"); 
			define("BASE", "id10733680_webpoll");
		}
	//connect to database
		$connection = mysqli_connect(HOST, USER, PASS, BASE);

	//query command to select the id and film names from my database 
		$sql = "SELECT id, film_name FROM `poll_choices` WHERE 1";

	//run command..make sure my computer is connected to the database or die 
		$results = mysqli_query($connection, $sql) or die("Can't connect today: ".mysqli_connect_error()); 

	//!isset means the vote button is empty IT HAS NOT BEEN PRESSED YET 
	//if the vote button has not been pressed yet, display the choices to vote on for the user 
		if(!isset($_POST['vote']))
		{
				echo '<div class="headWrap">'; 
					echo '<h1 class ="theGiantHead">Vote on the Best Show Ever!</h1>';
				echo '</div>';
				
				echo "<form action='index.php' method='POST'>";
				//loop through the results and store them within an array
				while($rows = mysqli_fetch_array($results, MYSQLI_ASSOC))
				{
					
					echo '<div class="output">'; 
				//print out the field names in the database and put radio buttons next to each of them
					echo "<input type='radio' class='radioButts' name='pick' value='" .$rows['film_name']."'>" .$rows['film_name'];
					echo '</div>';
					echo "<br>";
				}
			echo '<div class="buttDiv" id="buttTest">';
				echo '<button onClick ="hide()" type="submit" name="vote" value="Vote" class="submitb" id="thebutton">Vote</button>';
				echo '<input type="hidden" name="submitted" value="true">';
			echo '</div>';//div to close the buttDiv 
			echo '</form>';
		}
		else if(isset($_POST['pick']))
		{
			//update the choices in the database based on the pick made by user
				$polloption = $_POST['pick'];
			//update the pick made by the user and increment it by one 
				$vote_query = ('UPDATE poll_choices SET pick = pick + 1 WHERE film_name = "' . $_POST['pick'] . '"');
			//run the command 
				mysqli_query($connection, $vote_query);
				#echo $polloption;
				
			echo '<div class="percent">';
				//the user is a good person and did what they were supposed to do and voted :)
					echo '<h1 class="thankYou">Thank you for voting! Here are your results:</h1>';
					echo '<br>';
					echo '<br>';
				//turn the votes into a percentage 
					$percent_query = ('SELECT id, film_name, (pick/B.SV)*100 AS percentage FROM poll_choices CROSS JOIN (SELECT SUM(pick) SV FROM poll_choices) B GROUP BY id, film_name;');
					$res = mysqli_query($connection, $percent_query);
					
					while($row = mysqli_fetch_array($res, MYSQLI_ASSOC))
					{
						//echo $row['film_name'];
						$final = number_format($row['percentage'],2);
						echo '<p class ="final"> '. $row["film_name"] .': '. $final .'% </p>';
					}
					echo '<h2 class="voteagain"><a href="index.php">Vote again if you wanna!"</a></h2>';
			echo '</div>';//end div tag to percent 
				
		}
		//if the user didn't even select anything
			else if(empty($_POST['pick']))
			{
				echo '<div class="noVote">';
					echo '<h1 class="nope"> <a href="index.php"> you didnt vote..try again! </a></h1>';
				echo '</div>';
			}
?>
<script src="js/script.js"></script>
</body>
</html>