# Web API for Bowling Lottery

The API is based on MySQL and PHP. 

## Setup

Edit MySQL database server, user name, password, and scheme name in `api/db.php`:

```php
	$db_servername = "localhost";	// Database server
	$db_username = "dbadmin";		// Database user name 
	$db_password = "123456";		// Database user password
	$db_dbname = "bowling";			// Scheme name
```

Run `api/db_setup.php` in web browser.

## Usage - Bowlers

### Create a new bowler

`POST /api/bowler.php`

(Returns the id of the bowler added)

Sample Request
```json
{
	"firstname":"Xiaotian",
	"lastname":"Le",
	"email":"bellle126@gmail.com",
	"password":"123456"
}
```

Sample Response
```json
{"id":"1"}
```

### Get all bowlers

`GET /api/bowler_list.php`

Sample Response
```json
[
{
    "id":"1",
    "firstname":"Xiaotian",
    "lastname":"Le",
    "email":"bellle126@gmail.com",
    "reg_date":"2015-11-12 01:11:15",
    "payouts":"303"
},
{
    "id":"2",
    "firstname":"Xiaotian",
    "lastname":"Le",
    "email":"lxtbell@gmail.com",
    "reg_date":"2015-11-12 00:25:25",
    "payouts":"347"
}
]
```

### Get a specific bowler

`GET /api/bowler.php`

Sample Request
```json
{"bowler_id":"1"}
```

Sample Response
```json
{
	"id":"1",
	"firstname":"Xiaotian",
	"lastname":"Le",
	"email":"bellle126@gmail.com",
	"reg_date":"2015-11-12 01:11:15",
	"payouts":"303"
}
```

## Usage - Leagues

### Create a new league

`POST /api/league.php`

(Returns the id of the league added)

Sample Request
```json
{
	"name":"Alpha",
	"descr":"Description of the Alpha League",
	"capacity":"0"
}
```

Sample Response
```json
{"id":"1"}
```

### Get all leagues

`GET /api/league_list.php`

Sample Response
```json
[
{
	"id":"1",
	"name":"Alpha",
	"ticket_price":"1",
	"estab_date":"2015-11-12 01:11:15",
	"descr":"Alpha Desc",
	"capacity":"3",
	"lottery_pool":"0",
	"lottery_id":"201546",
	"lottery_winner":"2"
},
{
	"id":"2",
	"name":"Beta",
	"ticket_price":"1",
	"estab_date":"2015-11-12 00:27:09",
	"descr":"Beta Desc",
	"capacity":"0",
	"lottery_pool":"0",
	"lottery_id":"201546",
	"lottery_winner":null
}
]
```

### Get a specific league

`GET /api/league.php`

Sample Request
```json
{"league_id":"1"}
```

Sample Response
```json
{
	"id":"1",
	"name":"Alpha",
	"ticket_price":"1",
	"estab_date":"2015-11-12 01:11:15",
	"descr":"Alpha Desc",
	"capacity":"3",
	"lottery_pool":"0",
	"lottery_id":"201546",
	"lottery_winner":"2"
}
```

### Add a bowler to a league

`POST /api/league_members.php`

(Returns the corresponding record id)

Sample Request
```json
{
	"league_id":"1",
	"bowler_id":"1"
}
```

Sample Response
```json
{"id":"1"}
```

### Get all bowlers in a league

`GET /api/league_members.php`

Sample Request
```json
{"league_id":"1"}
```

Sample Response
```json
[
{
	"id":"2",
	"league_id":"1",
	"bowler_id":"1",
	"join_date":"2015-11-12 00:25:54"
},
{
	"id":"3",
	"league_id":"1",
	"bowler_id":"2",
	"join_date":"2015-11-12 00:26:02"
}
]
```

## Usage - Lotteries

### Purchase a ticket for a bowler for a lottery

`POST /api/lottery_tickets.php`

(Returns the lottery event id, the new amount of tickets owned, and the new lottery pool)

Sample Request
```json
{
	"league_id":"1",
	"bowler_id":"1",
	"tickets":"100"
}
```

Sample Response
```json
{
	"lotteryId":"201546",
	"newTickets":"100",
	"newLotteryPool":"200"
}
```

### Get all tickets for a bowler for a lottery

`GET /api/lottery_tickets.php`

Sample Request
```json
{
	"league_id":"1",
	"bowler_id":"1",
}
```

Sample Response
```json
{
	"lotteryId":"201546",
	"newTickets":"100",
	"newLotteryPool":"200"
}
```

### Draw a winner for a lottery

`POST /api/lottery_winner.php`

(Returns the lottery event id, the lottery pool, and the winner's id)

Sample Request
```json
{
	"league_id":"1",
}
```

Sample Response
```json
{
	"lotteryId":201546,
	"lotteryPool":"100",
	"lotteryWinner":"2"
}
```

### Get the winner for a lottery

`GET /api/lottery_winner.php`

Sample Request
```json
{
	"league_id":"1",
}
```

Sample Response
```json
{
	"lotteryId":201546,
	"lotteryPool":"100",
	"lotteryWinner":"2"
}
```

### Record the winning bowler's roll

`POST /api/lottery_attempt.php`

(Returns the lottery event id, the new lottery pool, the new payouts, and the money earned in the event)

Sample Request
```json
{
	"league_id":"1",
	"bowler_id":"2",
	"pins_knocked":"10"
}
```

Sample Response
```json
{
	"lotteryId":201546,
	"newLotteryPool":0,
	"newPayouts":100,
	"earned":"100"
}
```

### Get the winning bowler's roll

`GET /api/lottery_attempt.php`

Sample Request
```json
{
	"league_id":"1",
	"bowler_id":"2",
	"pins_knocked":"10"
}
```

Sample Response
```json
{
	"lotteryId":201546,
	"newLotteryPool":0,
	"newPayouts":100,
	"earned":"100"
}
```
