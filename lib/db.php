<?php
$dbHost = getenv('DB_HOST');
$dbPort = getenv('DB_PORT');
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');

$dbHost = $dbHost === false ? 'localhost' : $dbHost;
$dbPort = $dbPort === false || $dbPort === '' ? 3306 : (int) $dbPort;
$dbName = $dbName === false ? 'martdevelopers_iLibrary' : $dbName;
$dbUser = $dbUser === false ? 'root' : $dbUser;
$dbPassword = $dbPassword === false ? '' : $dbPassword;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);
