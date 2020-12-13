# Introduction

Implement a simple application should display an interface with a button and a single input that represents the requested quantity of a product.
                   
When the button is clicked, the interface should show either the $ value of the quantity of that product that will be applied, or an error message if the quantity to be applied exceeds the quantity on hand.

Note that product purchased first should be used first, therefore the quantity on hand should be the most recently purchased.

Here is a small example of inventory movements:
```
a. Purchased 1 unit at $10 per unit
b. Purchased 2 units at $20 per unit
c. Purchased 2 units at $15 per unit
d. Applied 2 units
```
 

After the 2 units have been applied, the purchased units in 'a' have been completely used up. Only 1 unit from 'b' has been used, so the remaining inventory looks like this:

```
b. 1 unit at $20 per unit 
c. 2 units at $15 per unit
```

Quantity on hand = 3 Valuation = (1 * 20) + (2 * 15) = $50

# Requirements

If you don't want to use Docker as the basis for your running environment, you need to make sure that your operating environment meets the following requirements:
   
- PHP >= 7.4
- Composer >= 2.0.2
- Swoole PHP extension >= 4.4，and Disabled `Short Name`
- JSON PHP extension
 
# Installation

Execute the following command to create a copy of the `inventory` project.

```
git clone git@github.com:ivannis/inventory.git
cd inventory
cp .env.example .env
docker-compose build inventory
docker-compose up -d
docker exec -it inventory sh
composer install
php bin/hyperf.php migrate:fresh
php bin/hyperf.php app:install
```

# Usage

Once installed, you can run the following commands to start the API server:

```
docker exec -it inventory sh
composer start
# press CTRL + C to terminate the current process
```

Now you can check the API at the endpoint: `http://0.0.0.0:9501`.

Open another terminal and run the following commands to start the Frontend application:
```
docker exec -it inventory sh
composer serve
# press CTRL + C to terminate the current process
```

Now you can check the application at the endpoint: `http://0.0.0.0:5000`.

# Architecture

This example was implemented with a basic architecture. You can find a solution with event-based architecture in the following [branch](https://github.com/ivannis/inventory/tree/event-driven)

# Tests

```
docker exec -it inventory sh
composer test
```
