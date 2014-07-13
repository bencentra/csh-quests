<?php
// Include the database connection info
require_once("../../quests_db_info.inc");

// Determine how many quests to get
$numQuests = 6;
if (isset($_GET['n'])) {
	$numQuests = $_GET['n'];
	if ($numQuests > 12) {
		$numQuests = 12;
	} else if ($numQuests < 3) {
		$numQuests = 3;
	}
}

// Get some random quests
$result = $pdo->query("SELECT id, name, info FROM quests ORDER BY RAND() LIMIT $numQuests");
$quests = $result->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE HTML>
<html>
<head>
	<title>CSH Quest Picker</title>
	<meta name="viewport" content="width=device-width" />
	<link rel="stylesheet" type="text/css" href="css/quests.css"/>
	<script type="text/javascript" src="js/quests.js"></script>
	<script type="text/javascript">	
		// Set some variables
		quests = <?php echo json_encode($quests); ?>; 
		numSlices = <?php echo $numQuests; ?>

		console.log(quests);

		document.addEventListener("DOMContentLoaded", function() {
			// Get a handle to all necessary DOM elements
			wheel = document.getElementById("spinner-board"); // DOM Object for the spinner board
			arrow = document.getElementById("spinner-arrow"); // DOM Object for the spinner arrow
			spinButton = document.getElementById("quest-button"); // DOM Object for the spin wheel <button>
			questName = document.getElementById("quest-name"); // DOM Object for the quest name <span>
			questInfo = document.getElementById("quest-info"); // DOM Object for the quest info <p>
			// Generate the wheel sections and populate them with the quest data
			for (var i = 0; i < numSlices; i++) {
				slices[i] = new Slice(i, wheel, quests[i]);
			}
			// Highlight the first slice
			slices[0].toggleOverlay();
		}, false);

		function getRandomQuest() {
			var rando = Math.floor(Math.random() * quests.length);
			questName.innerHTML = quests[rando].name;
			questInfo.innerHTML = quests[rando].info;
		}

	</script>
</head>
<body>
<div id="wrapper">
	<header>
		<a href="index.php"><span id="page-title">CSH Quests</span></a>
		<nav id="navbar">
			<ul>
				<li><a class="navitem navitem-current" href="index.php">Home</a></li>
				<li><a class="navitem" href="addquest.php">Add Quest</a></li>
				<li><a class="navitem" href="allquests.php">All Quests</a><li>
			</ul>
		</nav>
		<br class="clearfix"/>
	</header>
	<br/>
	<section id="main">
		<h2>Quest Spinner</h2>
	</section>
	<section id="left">
	<div>
		<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="400" height="400">
			<circle cx="200" cy="200" r="180" fill="#222222"/>
			<g id="spinner-board"></g>
			<path id="spinner-arrow" d="M 195 200 L 195 70 L 188 70 L 200 55 L 212 70 L 205 70 205 200 Z" fill="#EEEEEE" stroke="#222222" style="stroke-width:2px"/>
			<circle cx="200" cy="200" r="18" fill="#444444" stroke="#222222" style="stroke-width:2px"/>
			<circle cx="200" cy="200" r="9" fill="#666666" stroke="#222222" style="stroke-width:2px"/>
		</svg>
	</div>
	</section>
	<section id="right">
	<div>
		<button id="quest-button" onclick="toggleSpinning();">Start the Spinner!</button>
		<div id="quest">
			<span id="quest-name">Hello There!</span>
			<p id="quest-info">Hit the big button to start the spinner and get your quest!</p>
		</div>
		<br/>
		<p><button onclick="getRandomQuest();">Just Give Me a Quest!</button></p>
		<div id="options">
			<p>Reload with <input type="number" id="newQuestCount" value="<?php echo $numQuests; ?>" size="1" min="3" max="12"/> new quests: <button id="newQuestButton" onclick="generateNewQuests();">Go!</button>
		</div>
	</div>
	</section>
	<br class="clearfix"/>
	<footer>
		<p>Made by Ben Centra.</p>
		<p><a href="#">Check out the Quests API!</a></p>
	</footer>
	<div id="bottom"></div>
</div>
</body>
</html>