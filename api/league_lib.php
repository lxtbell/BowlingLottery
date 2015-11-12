<?php
require_once "db.php";

function findLeagueRecord($conn, $leagueId) {
	global $db_table_leagues;
	
	$sql = "SELECT * FROM $db_table_leagues WHERE id = :id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':id', $leagueId, PDO::PARAM_INT);
	$stmt->execute();

	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

	if ($stmt->rowCount() == 0)
		return null;

	return $stmt->fetch();
}

function findLeagueBowlersCount($conn, $leagueId) {
	global $db_table_league_bowlers;

	$sql = "SELECT COUNT(league_id) AS totalBowlers FROM $db_table_league_bowlers WHERE league_id = :league_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':league_id', $leagueId, PDO::PARAM_INT);
	$stmt->execute();

	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt->fetch()["totalBowlers"];
}

function findLeagueBowlerRecord($conn, $leagueId, $bowlerId) {
	global $db_table_league_bowlers;

	$sql = "SELECT * FROM $db_table_league_bowlers WHERE league_id = :league_id AND bowler_id = :bowler_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':league_id', $leagueId, PDO::PARAM_INT);
	$stmt->bindParam(':bowler_id', $bowlerId, PDO::PARAM_INT);
	$stmt->execute();

	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

	if ($stmt->rowCount() == 0)
		return null;

	return $stmt->fetch();
}
?>
