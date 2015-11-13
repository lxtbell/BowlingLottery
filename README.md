# Web API for Bowling Lottery

The API is based on MySQL and PHP. 

## Setup

Edit MySQL database server, user name, and password in `/api/db.php`.

```php
$db_servername = "localhost";					// Database server
$db_dbname = "bowling";      					// Scheme name
$db_username = "dbadmin";    					// Database user name 
$db_password = "123456";     					// Database user password
```

Run `/api/db_setup.php` in web browser to create the scheme and the tables.

## Usage

All the following usages can be found in `index.html`.

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
{
	"id":"1"
}
```

### Get all bowlers

`GET /api/bowler_list.php`

Sample Response
```json
[{
	"id":"1",
	"firstname":"Xiaotian",
	"lastname":"Le",
	"email":"bellle126@gmail.com",
	"reg_date":"2015-11-12 12:41:44",
	"payouts":"30"
},
{
	"id":"2",
	"firstname":"Xiaotian",
	"lastname":"Le",
	"email":"lxtbell@gmail.com",
	"reg_date":"2015-11-12 12:41:55",
	"payouts":"0"
}]
```

### Get a specific bowler

`GET /api/bowler.php`

Sample Request
```json
{
	"bowler_id":"1"
}
```

Sample Response
```json
{
	"id":"1",
	"firstname":"Xiaotian",
	"lastname":"Le",
	"email":"bellle126@gmail.com",
	"reg_date":"2015-11-12 01:11:15",
	"payouts":"30"
}
```

## Usage - Leagues

### Create a new league

`POST /api/league.php`

(Set capacity to 0 for unlimited capacity)
(Returns the id of the league added)

Sample Request
```json
{
	"name":"Alpha",
	"descr":"Description of Alpha League",
	"capacity":"0"
}
```

Sample Response
```json
{
	"id":"1"
}
```

### Get all leagues

`GET /api/league_list.php`

Sample Response
```json
[{
	"id":"1",
	"name":"Alpha",
	"ticket_price":"1",
	"estab_date":"2015-11-12 12:45:53",
	"descr":"Description of Alpha League",
	"capacity":"0",
	"lottery_pool":"270",
	"lottery_id":"201546",
	"lottery_winner":"1"
},
{
	"id":"2",
	"name":"Beta",
	"ticket_price":"1",
	"estab_date":"2015-11-12 12:43:50",
	"descr":"Description of Beta League",
	"capacity":"10",
	"lottery_pool":"0",
	"lottery_id":null,
	"lottery_winner":null
}]
```

### Get a specific league

`GET /api/league.php`

Sample Request
```json
{
	"league_id":"1"
}
```

Sample Response
```json
{
	"id":"1",
	"name":"Alpha",
	"ticket_price":"1",
	"estab_date":"2015-11-12 12:45:53",
	"descr":"Description of Alpha League",
	"capacity":"0",
	"lottery_pool":"270",
	"lottery_id":"201546",
	"lottery_winner":"1"
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
{
	"id":"1"
}
```

### Get all bowlers in a league

`GET /api/league_members.php`

Sample Request
```json
{
	"league_id":"1"
}
```

Sample Response
```json
[{
	"id":"1",
	"league_id":"1",
	"bowler_id":"1",
	"join_date":"2015-11-12 12:44:01"
},
{
	"id":"2",
	"league_id":"1",
	"bowler_id":"2",
	"join_date":"2015-11-12 12:44:02"
}]
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
	"lotteryId":201546,
	"newTickets":100,
	"newLotteryPool":300
}
```

### Get ticket price and tickets bought for a bowler for a lottery

`GET /api/lottery_tickets.php`

Sample Request
```json
{
	"league_id":"1",
	"bowler_id":"1"
}
```

Sample Response
```json
{
	"ticketPrice":"1",
	"lotteryId":201546,
	"tickets":"100",
	"lotteryPool":"300"
}
```

### Draw a winner for a lottery

`POST /api/lottery_winner.php`

(Returns the lottery event id, the lottery pool, and the winner's id)

Sample Request
```json
{
	"league_id":"1"
}
```

Sample Response
```json
{
	"lotteryId":201546,
	"lotteryPool":"270",
	"lotteryWinner":"1"
}
```

### Get the winner for a lottery

`GET /api/lottery_winner.php`

Sample Request
```json
{
	"league_id":"1"
}
```

Sample Response
```json
{
	"lotteryId":201546,
	"lotteryPool":"270",
	"lotteryWinner":"1"
}
```

### Record the winning bowler's roll

`POST /api/lottery_attempt.php`

(Returns the lottery event id, the new lottery pool, the new payouts, and the money earned in the event)

Sample Request
```json
{
	"league_id":"1",
	"bowler_id":"1",
	"pins_knocked":"5"
}
```

Sample Response
```json
{
	"lotteryId":201546,
	"newLotteryPool":270,
	"newPayouts":60,
	"earned":"30"
}
```

### Get the winning bowler's roll

`GET /api/lottery_attempt.php`

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
	"pins_knocked":"5"
}
```

## Exceptions

All the exception texts are defined in `/api/util_errors.php`.

Sample Response in Case of an Exception
```json
{
	"error":"Bowler not found."
}
```
