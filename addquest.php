<?php
// Include the database connection info
require_once("../../quests_db_info.inc");

// Variable to save result of the database query
$result = false;

// Check if the form was submitted
if (isset($_POST['add_name']) && isset($_POST['add_info'])) {
	// Prepare the query
	$stmt = $pdo->prepare("INSERT INTO quests (name, info) VALUES (:name, :info)");
	$stmt->bindParam(":name", $_POST['add_name']);
	$stmt->bindParam(":info", $_POST['add_info']);
	// Make the query
	$result = $stmt->execute();
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>CSH Quest Picker</title>
	<meta name="viewport" content="width=device-width" />
	<link rel="stylesheet" type="text/css" href="css/quests.css"/>
	<!--<script type="text/javascript" src="js/quests.js"></script>-->
	<script type="text/javascript">	
		document.addEventListener("DOMContentLoaded", function() {
			var alerts = document.querySelectorAll(".alert");
			for (var i = 0; i < alerts.length; i++) {
				var alert = alerts[i];
				alert.addEventListener("click", function() {
					alert.setAttribute("style", "display:none;");
				}, false);
			}
		}, false);
	</script>
</head>
<body>
<div id="wrapper">
	<header>
		<a href="index.php"><span id="page-title">CSH Quests</span></a>
		<nav id="navbar">
			<ul>
				<li><a class="navitem" href="index.php">Home</a></li>
				<li><a class="navitem navitem-current" href="addquest.php">Add Quest</a></li>
				<li><a class="navitem" href="allquests.php">All Quests</a><li>
			</ul>
		</nav>
		<br class="clearfix"/>
	</header>
	<br/>
	<section id="main">
		<?php 
			// If the user hasn't been here before, display the warning message
			if (!isset($_COOKIE['csh_quests'])) {
				echo '<div class="alert info">Before adding a quest, look through the <a href="allquests.php">list of quests</a> to see if it has already been added!</div>';
				// Set a cookie so the user doesn't see the message again 
				setcookie("csh_quests", "true", time() + 60 * 60 * 24 * 360);
			} 

			// If the form was just submitted, show a success or error message
			if (isset($_POST['add_name']) && isset($_POST['add_info'])) {
				if ($result) {
					echo '<div class="alert success">Your quest has been submitted!</div>';
				}
				else {
					echo '<div class="alert warning">There was an error submitting your quest, please try again!</div>';
				}
			}
		?>
		<h2>Add a New Quest</h2>
		<form action="addquest.php" method="POST" id="add_form">
			<div class="form-label">
				<label for="add_name">Quest Title:</label>
			</div>
			<div class="form-field">
				<input type="text" id="add_name" name="add_name" maxlength="100" required/>
				<br/>
				<span class="small">(100 Characters Max)</span>
			</div>
			<br class="clearfix"/>
			<br/>
			<div class="form-label">
				<label for="add_info">Quest Info:</label>
			</div>
			<div class="form-field">
				<textarea id="add_info" name="add_info" maxlength="100" required></textarea>
				<br/>
				<span class="small">(255 Characters Max)</span>
			</div>
			<br class="clearfix"/>
			<br/>
			<input type="submit" id="add_submit" name="add_submit" value="Submit"/>
		</form>
	</section>
	<br class="clearfix"/>
	<footer>
		<p>Made by Ben Centra.</p>
		<p><a href="https://github.com/bencentra/csh-quests/blob/master/api/README.md">Check out the Quests API!</a></p>
	</footer>
	<div id="bottom"></div>
</div>
</body>
</html>