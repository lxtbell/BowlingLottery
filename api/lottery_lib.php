<?php
require_once "db.php";

define("BAD_LOTTERY_ID", 0);

// Unique for a specific week
function lotteryId() {
	$date = new DateTime();
	return ((int) $date->format("Y")) * 100 + ((int) $date->format("W"));		//e.g. 201501 = 1st Week of 2015
}

// Fetch current LotteryId. Update league data if they are outdated.
function updateLeagueLottery($conn, $leagueId) {
	global $db_table_leagues;
	
	$record = findLeagueRecord($conn, $leagueId);
	if (is_null($record)) {
		return BAD_LOTTERY_ID;
	}
	
	$lastLotteryId = $record["lottery_id"];
	$curLotteryId = lotteryId();
	if ($lastLotteryId != $curLotteryId) {
		//Update League Lottery
		$sql = "UPDATE $db_table_leagues SET lottery_id = $curLotteryId, lottery_winner = NULL WHERE id = $leagueId";
		$conn->exec($sql);
	}
	return $curLotteryId;
}

function createLotteryRecord($conn, $leagueId, $bowlerId, $lotteryId) {
	global $db_table_lotteries;
	
	$sql = "INSERT INTO $db_table_lotteries (league_id, bowler_id, lottery_id) VALUES ($leagueId, $bowlerId, $lotteryId)";
	$conn->exec($sql);
	$lastId = $conn->lastInsertId("id");
	
	$sql = "SELECT * FROM $db_table_lotteries WHERE id = :id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':id', $lastId, PDO::PARAM_INT);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt->fetch();
}

function findLotteryRecord($conn, $leagueId, $bowlerId, $lotteryId) {
	global $db_table_lotteries;
	
	$sql = "SELECT * FROM $db_table_lotteries WHERE league_id = :league_id AND bowler_id = :bowler_id AND lottery_id = :lottery_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':league_id', $leagueId, PDO::PARAM_INT);
	$stmt->bindParam(':bowler_id', $bowlerId, PDO::PARAM_INT);
	$stmt->bindParam(':lottery_id', $lotteryId, PDO::PARAM_INT);
	$stmt->execute();
	
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	
	if ($stmt->rowCount() == 0)
		return createLotteryRecord($conn, $leagueId, $bowlerId, $lotteryId);
	
	return $stmt->fetch();
}

function findLotterySum($conn, $leagueId, $lotteryId) {
	global $db_table_lotteries;

	$sql = "SELECT SUM(tickets) AS totalTickets FROM $db_table_lotteries WHERE league_id = :league_id AND lottery_id = :lottery_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':league_id', $leagueId, PDO::PARAM_INT);
	$stmt->bindParam(':lottery_id', $lotteryId, PDO::PARAM_INT);
	$stmt->execute();

	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt->fetch()["totalTickets"];
}

function findLotteryWinner($conn, $leagueId, $lotteryId, $ticketId) {
	global $db_table_lotteries;

	// Use SQL arithmetics to save bandwidth
	// Choose the first bowler with ticket partial sum larger than or equal to $ticketId
	$sql = "
	SELECT SummedLotteries.* FROM (
		SELECT
			RawLotteries.*,
			@prevTickets := @prevTickets + RawLotteries.tickets AS PartialTicketSum
		FROM
			$db_table_lotteries AS RawLotteries, (SELECT @prevTickets := 0) AS InitVars
		WHERE league_id = :league_id AND lottery_id = :lottery_id
	) As SummedLotteries
	WHERE
		SummedLotteries.PartialTicketSum >= :ticket_id
	LIMIT 1";
	
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':league_id', $leagueId, PDO::PARAM_INT);
	$stmt->bindParam(':lottery_id', $lotteryId, PDO::PARAM_INT);
	$stmt->bindParam(':ticket_id', $ticketId, PDO::PARAM_INT);
	$stmt->execute();

	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	return $stmt->fetch();
}
?>