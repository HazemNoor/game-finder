## About

This laravel application provides an API that displays information about a video game fetched from list of websites

- [RAWG Video Games Database API](https://api.rawg.io/docs/)
- [IGDB.com](https://api-docs.igdb.com/ )

## Installation
1. Fetch project

```shell
git clone git@github.com:HazemNoor/game-finder.git
cd game-finder
```

2. Copy file `.env.example` into `.env`

```shell
cp .env.example .env
```

3. Edit file `.env` adding values for these constants

```text
TWITCH_CLIENT_ID=
TWITCH_CLIENT_SECRET=
RAWG_CLIENT_API_KEY=
```

Get these values from their websites
- [RAWG Video Games Database API](https://api.rawg.io/docs/)
- [IGDB.com](https://api-docs.igdb.com/ )

4. You many need to update these values related to `Docker` in `.env`

```text
SERVER_HTTP_PORT=80
SERVER_HTTPS_PORT=443

USER_ID=1000
GROUP_ID=1000
```

5. Make sure to have `Docker` installed on your machine, then execute this command to build docker images

```shell
make build
```

6. Run this command to execute `composer install`

```shell
make up
make install
```

## Other commands

- Run tests

```shell
make up
make test
```

- If you need to log in to docker container, use these commands

```shell
make up
make login
```

- Stop docker containers

```shell
make down
```

## API

The provided API is
```
GET http://localhost/api/games

Query Parameter
search=Red Alert 2

Header
Accept: application/vnd.api+json
```

**Example API call via CURL**

```shell
curl --request GET \
  --url 'http://localhost/api/games?search=Red%20Alert%202' \
  --header 'Accept: application/vnd.api+json'
```

**Sample successful response body**

```json
{
  "results": [
    {
      "name": "Command & Conquer: Red Alert 2",
      "image": "https:\/\/media.rawg.io\/media\/games\/673\/67304bfba37b6a18c50a60ab6ba6cebd.jpg"
    },
    {
      "name": "Command & Conquer: Red Alert",
      "image": "https:\/\/media.rawg.io\/media\/games\/e87\/e87bbd9feb37b226b1b6a4f11e9492a0.jpg"
    }
  ]
}
```

## How clients work together
- All Clients are registered to `GameFinder` through `GameFinderProvider`, you can add new clients there
```php
$finder->addClient($client);
```

- When searching for the Game data, all clients are used to search for Game data and results are aggregated and duplication is removed.

- Results are ordered the same way that is returned from Clients

- Priority of result in case of duplication is, what comes first is kept, what comes later is removed.

- If any Error happens in any Client, an Exception `ClientRuntimeException` is reported for further analysis later

## Testing
Testing is divided in two classes `BePark\GameFinder\Tests\ApiTest` and `BePark\GameFinder\Tests\GameFinderTest`
You can run unit test using these commands
```shell
make up
make test
```

## Coding Style
The coding style used is [PSR-12](https://www.php-fig.org/psr/psr-12/) and is included with the testing command `make test` using [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

## Todo

- Implement [Circuit Breaker Algorithm](https://martinfowler.com/bliki/CircuitBreaker.html) to prevent failures from constantly recurring after a certain threshold of failures in Clients

- Implement [apiDoc](https://apidocjs.com/) for API Documentation
