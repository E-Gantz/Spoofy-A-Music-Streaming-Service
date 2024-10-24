<?php
include "../modules/menubar.php";
include "../modules/mysql_connect.php";
require_once("../modules/python_connect.php");

if(!isset($_SESSION)) { session_start(); }
if (isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] && $_SESSION["Admin"]){

	// Define variables and initialize with empty values
	$error_string = "";
	$title = "";
	$duration = "";
	$filepath = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		// Validate title
		$title = trim($_POST["title"]);
		if(empty($title)) {
			$error_string = "Title can't be empty.";
		}
		
		// Validate duration
		$duration = trim($_POST["duration"]);
		if(empty($duration)) {
			$error_string = "Duration can't be empty.";     
		} elseif(!preg_match('/^[0-5][0-9]:[0-5][0-9]:[0-5][0-9]$/', trim($_POST["duration"]))) {
			$error_string = "Duration should be formatted as 'hh:mm:ss'";
		}
		
		// Validate filepath
		// TODO: Actual validation checks
		$filepath = trim($_POST["filepath"]);

		// If there are no errors, insert into the database
		if(empty($error_string)) {

			// Replace single quotes with escape characters
			$title = str_replace("'", "\'", $title);
			$duration = str_replace("'", "\'", $duration);
			$filepath = str_replace("'", "\'", $filepath);
			
			// Prepare an insert statement
			$sql = "INSERT INTO SONG (Title, Duration, MusicFile, TotalPlays, MonthlyPlays) VALUES ('$title', '$duration', '$filepath', 0, 0)";
			sendQuery($sql);
			header("Refresh:0; url=manage_songs.php");
		}
		
		// Close connection
		mysqli_close($con);
	}
} else {
	header("location: ../error.php");
}
?>

<html>
    <head>
		<link href="/styles/style.css" rel="stylesheet" />
        <title>Add a Song - Spoofy</title>
    </head>
    <body>
        <div class="wrapper">
        <h2>Add a Song</h2>
        <p>Fill in song information:</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<label>Song Title</label>
				<input type="text" name="title" placeholder="ex. YYZ" class="form-control" value="<?php echo $title; ?>"> 
				<label>Duration</label>
				<input type="text" name="duration" placeholder="hh:mm:ss" class="form-control" value="<?php echo $duration; ?>">
				<label>Song File Path</label>
				<input type="text" name="filepath" placeholder="ex. music/song.mp3" class="form-control" value="<?php echo $filepath; ?>">
				<input type="submit" class="submitForm" value="Submit">
				<input type="reset" class="btn btn-secondary ml-2" value="Reset">
                <?php if ($error_string) echo "<p style=\"color:red;\">".$error_string."</p>";?>
				<button onclick='location.href="manage_songs.php"' type='button'>

					Return to Manage Songs
				</button><br>
            </form>
        </div>
    </body>
</html>
