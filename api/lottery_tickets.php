<?php
/**
 * @author      Xiaotian Le
 * @version     1.0
 * @since       2015-11-10
 */
require_once "league_lib.php";
require_once "lottery_lib.php";

/** Purchase a ticket for a bowler for a lottery */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$leagueId = (int) purifyInput($_POST["league_id"]);
	$bowlerId = (int) purifyInput($_POST["bowler_id"]);
	$ticketChange = (int) purifyInput($_POST["tickets"]);

	try {
		$conn = connectDb();
		$lotteryId = updateLeagueLottery($conn, $leagueId);
		if ($lotteryId == BAD_LOTTERY_ID)
			return echoError(ERROR_LEAGUE_NOT_FOUND);
			
		$bowlerRecord = findLeagueBowlerRecord($conn, $leagueId, $bowlerId);
		if (is_null($bowlerRecord))
			return echoError(ERROR_BOWLERS_NOT_IN_LEAGUE);
		
		$lotteryRecord = findLotteryRecord($conn, $leagueId, $bowlerId, $lotteryId);
		$curTickets = $lotteryRecord["tickets"];
		$curTickets += $ticketChange;
		
		$leagueRecord = findLeagueRecord($conn, $leagueId);
		$lotteryPool = $leagueRecord["lottery_pool"];
		$lotteryPool += $ticketChange;
		
		$sql = "UPDATE $db_table_lotteries SET tickets = $curTickets WHERE league_id = $leagueId AND bowler_id = $bowlerId AND lottery_id = $lotteryId";
		$conn->exec($sql);
		
		$sql = "UPDATE $db_table_leagues SET lottery_pool = $lotteryPool WHERE id = $leagueId";
		$conn->exec($sql);
		echo encodeOuput(array("lotteryId" => $lotteryId, "newTickets" => $curTickets, "newLotteryPool" => $lotteryPool));
	}
	catch(PDOException $e) {
		processError($e);
	}
}

/** Get all tickets for a bowler for a lottery */
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$leagueId = (int) purifyInput($_GET["league_id"]);
	$bowlerId = (int) purifyInput($_GET["bowler_id"]);
	
	try {
		$conn = connectDb();
		$lotteryId = updateLeagueLottery($conn, $leagueId);
		if ($lotteryId == BAD_LOTTERY_ID)
			return echoError(ERROR_LEAGUE_NOT_FOUND);
		
		$bowlerRecord = findLeagueBowlerRecord($conn, $leagueId, $bowlerId);
		if (is_null($bowlerRecord))
			return echoError(ERROR_BOWLERS_NOT_IN_LEAGUE);
		
		$leagueRecord = findLeagueRecord($conn, $leagueId);
		
		$lotteryRecord = findLotteryRecord($conn, $leagueId, $bowlerId, $lotteryId);
		$ticketPrice = $leagueRecord["ticket_price"];
		$lotteryPool = $leagueRecord["lottery_pool"];
		$curTickets = $lotteryRecord["tickets"];
	
		echo encodeOuput(array("ticketPrice" => $ticketPrice, "lotteryId" => $lotteryId, "tickets" => $curTickets, "lotteryPool" => $lotteryPool));
	}
	catch(PDOException $e) {
		processError($e);
	}
}

?>