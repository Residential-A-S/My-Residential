<?php
const VERSION = '001';

require __DIR__ . '/../../public/config.php';

$db = new PDO("mysql:dbname=" . DB_NAME . ";host=localhost", DB_USER, DB_PASSWORD, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);


$files = glob(__DIR__ . '/../Migrations/'.VERSION.'-*.php');
sort($files);
foreach ($files as $file) {
    require $file;
    echo "Executed migration: $file\n";
}
