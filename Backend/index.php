<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once 'Config/db.php';
require_once 'Controllers/AuthController.php'; 

$db = new Database();
$conn = $db->getConnection();

$authController = new AuthController($conn);

require_once 'routes/api.php';
