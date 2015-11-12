<?php
require_once "league_lib.php";

// Establish a league
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = purifyInput($_POST["name"]);
	//$ticket_price = (int) purifyInput($_POST["ticket_price"]);
	$ticket_price = DEFAULT_TICKET_PRICE;									//DEFAULT_TICKET_PRICE = $1
	$descr = purifyInput($_POST["descr"]);
	$capacity = (int) purifyInput($_POST["capacity"]);
	//TODO Timezone Support
	
	if (!validateInput($name, "NAME"))
		return echoError(ERROR_INVALID_NAME);
	
	if (!validateInput($desc, "DESCR"))
		return echoError(ERROR_INVALID_DESCRIPTION);
	
	if ($capacity < 0)
		return echoError(ERROR_INVALID_CAPACITY);
	
	try {
		$conn = connectDb();
		$sql = "INSERT INTO $db_table_leagues (name, ticket_price, capacity, estab_date, descr) VALUES ('$name', $ticket_price, $capacity, NOW(), '$descr')";
		$conn->exec($sql);
		echo encodeOuput(array("id" => $conn->lastInsertId("id")));
	}
	catch(PDOException $e) {
		processError($e);
	}
}

// Request data about a league
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$leagueId = (int) purifyInput($_GET["league_id"]);
	
	try {
		$conn = connectDb();
		$record = findLeagueRecord($conn, $leagueId);
		
		if (is_null($record))
			return echoError(ERROR_LEAGUE_NOT_FOUND);
		echo encodeOuput($record);
	}
	catch(PDOException $e) {
		processError($e);
	}
}
?>
