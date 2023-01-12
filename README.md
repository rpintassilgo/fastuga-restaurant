# Fastuga

Fastuga was an university project done for the class Distributed Applications Development. Its purpose was to implement a management plataform for a fictional restaurant called Fastuga with a Single Page Application (SPA) on the frontend and a MVC(without views) architetural pattern on the backend that includes a Restful API.

Technologies used in the project: Vue, Laravel, MySQL

## How it works

User types:
> Customer: A customer can add/remove products from the menu to its cart and perform the payment\
> Delivery employee: Deliver order and see all the orders including its status and its order items status\
> Chef employee: Prepare hot dishes and set them to ready\
> Manager employee: Manage users, products and cancel orders

Order and order item status:
> Order: : 'P' (Preparing), 'R' (Ready), 'D' (Delivered) and 'C' (Cancelled)\
> Order item: 'W' (Waiting), 'P' (Preparing) and 'R' (Ready);

Modus Operandi:
> A customer adds products to his cart and performs the payment. A customer without account does not have access to discounts. If the cart does not include > hot dishes, the order status will be 'Ready' since the initial status from other order items such as cold dishes, drinks and desserts are always 'Ready'. > In this scenario, the delivery employee can deliver the order after the payment. If there are hot dishes, all the items from the order need to 'Ready' so > that the order can be 'Ready' and delivered by the delivery employee. To set all order items to 'Ready', a chef employee will need to prepare all the hot > dishes. After this, the delivery employee can deliver the order.

**Note: All user’s passwords of the provided data are “123”.**

## Backend configuration

Make a copy from .env.example and rename it as .env and configure it
```
APP_NAME=fastuga_back
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=fastuga_back
DB_USERNAME=
DB_PASSWORD=

PASSPORT_SERVER_URL="${APP_URL}"
PASSPORT_CLIENT_ID=2
PASSPORT_CLIENT_SECRET=

```

Install depencies, start your server, configure the database and install passport to generate its keys
```bash
$ composer install

$ php artisan migrate
$ php artisan db:seed

$ php artisan passport:install
```

## Frontend configuration

Again, make a copy from .env.example and rename it as .env and edit it as you wish
```
VITE_APP_BASE_URL=
VITE_APP_API_URL="${VITE_APP_BASE_URL}/api"
VITE_PAYMENT_SERVICE_URI=
```

Install depencies and start
```bash
$ npm install
$ npm run build
$ npm run preview
```
