<?php
// Include the database connection info
require_once("../../quests_db_info.inc");

$result = false; // Database query result
$numQuests = 20; // How many quests to retrieve
$page = 1; // "Page" of quests to retrieve

// Check if $_GET variables are present for page # and length
if (isset($_GET['p'])) {
	if ($_GET['p'] >= 0) {
		$page = $_GET['p'];
	}
}
if (isset($_GET['n'])) {
	if ($_GET['n'] >=5) {
		$numQuests = $_GET['n'];
	}
}

// Get the total number of quests 
$result = $pdo->query("SELECT COUNT(*) AS count FROM quests");
$totalQuests = $result->fetch(PDO::FETCH_ASSOC);
$totalQuests = $totalQuests["count"];

// Retrieve the quest info
$result = $pdo->query("SELECT * FROM quests ORDER BY ts ASC LIMIT ".($page-1)*$numQuests.", ".$numQuests);
$quests = $result->fetchAll(PDO::FETCH_ASSOC);

// Function for creating the page selector HTML
function createPageSelector($numQuests, $page, $totalQuests) {
	// Calculate some values
	$totalPages = ceil($totalQuests / $numQuests);
	$prevPage = ($page - 1 >= 1) ? $page - 1 : 1;
	$nextPage = ($page + 1 <= $totalPages) ? $page + 1: $totalPages;
	$newNumQuests = ($numQuests != 25) ? "&n=".$numQuests : "";
	// Enable/disable the prev/next buttons
	if ($page > 1) {
		$prevPageButton = '<a class="page" href="allquests.php?p='.$prevPage.$newNumQuests.'">&lt; Prev</a>';
	}
	else {
		$prevPageButton = '<span class="page">&lt; Prev</span>';
	}
	if ($page < $totalPages) {
		$nextPageButton = '<a class="page" href="allquests.php?p='.$nextPage.$newNumQuests.'">Next &gt;</a>';
	}
	else {
		$nextPageButton = '<span class="page">Next &gt;</span>';
	}
	// Form the HTML output
	$html = $prevPageButton;
	for ($i = 1; $i <= $totalPages; $i++) {
		if ($i != $page) {
			$html .= '<a class="page" href="allquests.php?p='.$i.'">'.$i.'</a>';
		}
		else {
			$html .= '<span class="page">'.$i.'</span>';
		}
	}
	$html .= $nextPageButton;
	// Return the HTML
	return $html;
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
		
	</script>
</head>
<body>
<div id="wrapper">
	<header>
		<a href="index.php"><span id="page-title">CSH Quests</span></a>
		<nav id="navbar">
			<ul>
				<li><a class="navitem" href="index.php">Home</a></li>
				<li><a class="navitem" href="addquest.php">Add Quest</a></li>
				<li><a class="navitem navitem-current" href="allquests.php">All Quests</a><li>
			</ul>
		</nav>
		<br class="clearfix"/>
	</header>
	<br/>
	<section id="main">
		<h2>All Quests</h2>
		<p><?php echo createPageSelector($numQuests, $page, $totalQuests); ?></p>
		<table id="quests">
			<tr><th>#</th><th>Quest Title</th><th>Description</th></tr>
			<?php 
				$count = ($page - 1) * $numQuests + 1;
				foreach($quests as $quest) {
					echo "<tr><td>".$count."</td><td>".$quest['name']."</td><td>".$quest['info']."</td></tr>";
					$count++;
				}
			?>
		</table>
		<p><?php echo createPageSelector($numQuests, $page, $totalQuests); ?></p>
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