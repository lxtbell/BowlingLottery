<?php
require_once "bowler_lib.php";
require_once "league_lib.php";
require_once "lottery_lib.php";

define("TARGET_PINS", 10);

// The winner attempts to get the strike
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$leagueId = (int) purifyInput($_POST["league_id"]);
	$bowlerId = (int) purifyInput($_POST["bowler_id"]);
	$pins_knocked = (int) purifyInput($_POST["pins_knocked"]);

	try {
		$conn = connectDb();
		$lotteryId = updateLeagueLottery($conn, $leagueId);
		if ($lotteryId == BAD_LOTTERY_ID)
			return echoError(ERROR_LEAGUE_NOT_FOUND);
		
		$leagueRecord = findLeagueRecord($conn, $leagueId);
		$lotteryPool = $leagueRecord["lottery_pool"];
		$lotteryWinnerId = $leagueRecord["lottery_winner"];
		
		if ($bowlerId != $lotteryWinnerId)
			return echoError(ERROR_NON_WINNER_ATTEMPTING);
		
		$bowlerRecord = findBowlerRecord($conn, $bowlerId);
		$bowlerPayouts = $bowlerRecord["payouts"];
		
		if ($pins_knocked >= TARGET_PINS)
			$earned = $lotteryPool;
		else
			$earned = (int) floor($lotteryPool / 10);
		$bowlerPayouts += $earned;
		$lotteryPool -= $earned;
		
		$sql = "UPDATE $db_table_lotteries SET pins_knocked = $pins_knocked WHERE league_id = $leagueId AND bowler_id = $bowlerId AND lottery_id = $lotteryId";
		$conn->exec($sql);
		
		$sql = "UPDATE $db_table_bowlers SET payouts = $bowlerPayouts WHERE id = $bowlerId";
		$conn->exec($sql);
		
		$sql = "UPDATE $db_table_leagues SET lottery_pool = $lotteryPool, lottery_winner = NULL WHERE id = $leagueId";
		$conn->exec($sql);
		echo encodeOuput(array("lotteryId" => $lotteryId, "newLotteryPool" => $lotteryPool, "newPayouts" => $bowlerPayouts, "earned" => $earned));
	}
	catch(PDOException $e) {
		processError($e);
	}
}

// Request data about the attempt
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$leagueId = (int) purifyInput($_GET["league_id"]);
	$bowlerId = (int) purifyInput($_GET["bowler_id"]);

	try {
		$conn = connectDb();
		$lotteryId = updateLeagueLottery($conn, $leagueId);
		if ($lotteryId == BAD_LOTTERY_ID)
			return echoError(ERROR_LEAGUE_NOT_FOUND);
		
		$sql = "SELECT pins_knocked FROM $db_table_lotteries WHERE league_id = $leagueId AND bowler_id = $bowlerId AND lottery_id = $lotteryId";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(":bowler_id", $bowlerId);
		$stmt->execute();
		
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		if ($stmt->rowCount() == 0)
			return echoError(ERROR_BOWLER_NOT_IN_LOTTERY);
		
		echo encodeOuput($stmt->fetch());
	}
	catch(PDOException $e) {
		processError($e);
	}
}

?>