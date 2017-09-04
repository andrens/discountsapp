<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

require "../src/discounts.php";
require "../src/products.php";
require "../src/items.php";
require "../src/rules.php";

$app->run();