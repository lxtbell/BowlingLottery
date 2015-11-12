<?php
require_once "bowler_lib.php";
require_once "league_lib.php";

// Add a bowler to a league
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$leagueId = (int) purifyInput($_POST["league_id"]);
	$bowlerId = (int) purifyInput($_POST["bowler_id"]);
	
	try {
		$conn = connectDb();
		
		$record = findLeagueRecord($conn, $leagueId);
		if (is_null($record))
			return echoError(ERROR_LEAGUE_NOT_FOUND);
		$capacity = $record["capacity"];
		
		$record = findBowlerRecord($conn, $bowlerId);
		if (is_null($record))
			return echoError(ERROR_BOWLER_NOT_FOUND);
		
		$record = findLeagueBowlerRecord($conn, $leagueId, $bowlerId);
		if (!is_null($record))
			return echoError(ERROR_BOWLER_ALREADY_JOIN_LEAGUE);

		$count = findLeagueBowlersCount($conn, $leagueId);
		if ($capacity != 0 && $count >= $capacity)
			return echoError(ERROR_LEAGUE_REACH_CAPACITY);
		
		$sql = "INSERT INTO $db_table_league_bowlers (league_id, bowler_id, join_date) VALUES ($leagueId, $bowlerId, NOW())";
		$conn->exec($sql);
		echo encodeOuput(array("id" => $conn->lastInsertId("id")));
	}
	catch(PDOException $e) {
		processError($e);
	}
}

// Return all bowlers in a league
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$leagueId = (int) purifyInput($_GET["league_id"]);

	try {
		$conn = connectDb();
		$sql = "SELECT * FROM $db_table_league_bowlers WHERE league_id = :league_id";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':league_id', $leagueId, PDO::PARAM_INT);
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		if ($stmt->rowCount() == 0)
			return echoError(ERROR_NO_BOWLER_IN_LEAGUE);
		
		echo encodeOuput($stmt->fetchAll());
	}
	catch(PDOException $e) {
		processError($e);
	}
}
?>
