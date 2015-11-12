<?php
require_once "league_lib.php";
require_once "lottery_lib.php";

// Roll the winner
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$leagueId = (int) purifyInput($_POST["league_id"]);

	try {
		$conn = connectDb();
		$lotteryId = updateLeagueLottery($conn, $leagueId);
		if ($lotteryId == BAD_LOTTERY_ID)
			return echoError(ERROR_LEAGUE_NOT_FOUND);
		
		$leagueRecord = findLeagueRecord($conn, $leagueId);
		$lotteryPool = $leagueRecord["lottery_pool"];
		
		$lotteryTickets = findLotterySum($conn, $leagueId, $lotteryId);
		if ($lotteryTickets <= 0)
			return echoError(ERROR_NO_BOWLER_IN_LOTTERY);
		$ticketId = mt_rand(1, $lotteryTickets);
		
		$lotteryWinner = findLotteryWinner($conn, $leagueId, $lotteryId, $ticketId);

		$lotteryWinnerId = $lotteryWinner["bowler_id"];
		$sql = "UPDATE $db_table_leagues SET lottery_winner = $lotteryWinnerId WHERE id = $leagueId";
		$conn->exec($sql);
		echo encodeOuput(array("lotteryId" => $lotteryId, "lotteryPool" => $lotteryPool, "lotteryWinner" => $lotteryWinnerId, ));
	}
	catch(PDOException $e) {
		processError($e);
	}
}

// Request data about the winner
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$leagueId = (int) purifyInput($_GET["league_id"]);

	try {
		$conn = connectDb();
		$lotteryId = updateLeagueLottery($conn, $leagueId);
		if ($lotteryId == BAD_LOTTERY_ID)
			return echoError(ERROR_LEAGUE_NOT_FOUND);
		
		$leagueRecord = findLeagueRecord($conn, $leagueId);
		$lotteryPool = $leagueRecord["lottery_pool"];
		$lotteryWinnerId = $leagueRecord["lottery_winner"];
		echo encodeOuput(array("lotteryId" => $lotteryId, "lotteryPool" => $lotteryPool, "lotteryWinner" => $lotteryWinnerId, ));
	}
	catch(PDOException $e) {
		processError($e);
	}
}

?>