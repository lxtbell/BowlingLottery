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

## Usage

## Bowlers

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
(Returns an array of bowler data including id, Email, payouts, firstname, lastname, and registration date)

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
(Returns the bowler data including id, Email, payouts, firstname, lastname, and registration date)

Sample Request
```json
{"id":"1"}
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

## Leagues

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
(Returns an array of league data)

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

### Get a specific league

`GET /api/league.php`
(Returns the league data)

Sample Request
```json
{"id":"1"}
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

```javascript
  client.joinLeague({
    bowlerId: 1,
    leagueId: 1,
    success: function(bowlers) {
      console.log(bowlers);
    },
    error: function(xhr)  {
      console.log(JSON.parse(xhr.responseText));
    }
  });
```

### Get all bowlers in a league

```javascript
  client.getBowlers({
    leagueId: 1,
    success: function(bowlers) {
      console.log(bowlers);
    },
    error: function(xhr)  {
      console.log(JSON.parse(xhr.responseText));
    }
  });
```

## Lotteries

### Purchase a ticket for a bowler for a lottery

```javascript
  client.purchaseTicket({
    bowlerId: 1,
    leagueId: 1,
    lotteryId: 1,
    success: function(ticket) {
      console.log(ticket);
    },
    error: function(xhr)  {
      console.log(JSON.parse(xhr.responseText));
    }
  });
```

### Get all tickets for a bowler for a lottery

```javascript
  client.getTickets({
    leagueId: 1,
    lotteryId: 1,
    success: function(lotteries) {
      console.log(lotteries);
    },
    error: function(xhr)  {
      console.log(JSON.parse(xhr.responseText));
    }
  });
```

### Draw a winner for a lottery

```javascript
  client.drawWinner({
    leagueId: 1,
    lotteryId: 1,
    success: function(roll) {
      console.log(roll);
    },
    error: function(xhr)  {
      console.log(JSON.parse(xhr.responseText));
    }
  });
```

### Get the winner for a lottery

### Record the winning bowler's roll

```javascript
  client.updateRoll({
    leagueId: 1,
    lotteryId: 1,
    pinCount: 7,
    success: function(roll) {
      console.log(roll);
    },
    error: function(xhr)  {
      console.log(JSON.parse(xhr.responseText));
    }
  });
```

### Get the winning bowler's roll
