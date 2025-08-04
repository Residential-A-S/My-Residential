<?php
require __DIR__ . '/config.php';

$db = new PDO("mysql:host=localhost", DB_USER, DB_PASSWORD, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// Reset database, only use this in development
$db->exec("DROP DATABASE IF EXISTS `" . DB_NAME . "`;");
$db->exec("
    CREATE DATABASE IF NOT EXISTS `propeteer_app_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
");

$db = new PDO("mysql:dbname=" . DB_NAME . ";host=localhost", DB_USER, DB_PASSWORD, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$files = glob(__DIR__ . '/../Migrations/*.php');
sort($files);
foreach ($files as $file) {
    require $file;
    echo "Executed migration: $file\n";
}
