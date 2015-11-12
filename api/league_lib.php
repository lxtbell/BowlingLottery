<?php
/**
 * @author      Xiaotian Le
 * @version     1.0
 * @since       2015-11-10
 */
require_once "db.php";

/**
 * Find the record for a specific league
 * @param PDO $conn PDO object to the database
 * @param int $leagueId League's id
 * @return array An row of league data
 */
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

/**
 * Find the total number of bowlers in a specific league
 * @param PDO $conn PDO object to the database
 * @param int $leagueId League's id
 * @return int The total number
 */
function findLeagueBowlersCount($conn, $leagueId) {
	global $db_table_league_bowlers;

	$sql = "SELECT COUNT(league_id) AS totalBowlers FROM $db_table_league_bowlers WHERE league_id = :league_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':league_id', $leagueId, PDO::PARAM_INT);
	$stmt->execute();

	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt->fetch()["totalBowlers"];
}

/**
 * Find the record for a specific bowler in a specific league
 * @param PDO $conn PDO object to the database
 * @param int $leagueId League's id
 * @param int $bowlerId Bowler's id
 * @return array An row of bowler data in the league
 */
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
