## How to start the project

These are following steps to setup project:

```
cp .env.dist .env
```

then to prepare docker environment just run the following command in the project directory:
```
make
```

to run the api tests, just run the following command:
```
make test-api
```

to run the phpunit tests, just run the following command:
```
make phpunit
```

`Note: if your local machine port 80 preoccupied, then please set NGINX_PORT to a free one in .env file and use it in the urls`

Endpoints:
``` 
POST http://127.0.0.1/vouchers with the following payload in order to create a voucher:

{
    amount: 10,
    valid_until: "2024-01-01 23:59:59"
}

GET http://127.0.0.1/vouchers?state=active -> to list the active vouchers
GET http://127.0.0.1/vouchers?state=inactive -> to list the inactive vouchers

PUT http://127.0.0.1/vouchers/{id} with the payload:
{
    amount: 10,
    valid_until: "2024-01-01 23:59:59"
}

DELETE http://127.0.0.1/vouchers/{id}

to create an order without voucher
POST http://127.0.0.1/orders 
{
   amount: 10
}

to create an order with voucher
POST http://127.0.0.1/orders
{
   amount: 10,
   voucher_code: "1234"
}

List of orders
GET http://127.0.0.1/orders 

```
Finally to shutdown the app run:

```
make down 
```

