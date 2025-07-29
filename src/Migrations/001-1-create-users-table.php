<?php
global $db;
$db->exec("
    CREATE TABLE `users` (
        id int(10) NOT NULL AUTO_INCREMENT, 
        email varchar(255) NOT NULL, 
        password varchar(255) NOT NULL, 
        created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        last_login_at timestamp NULL, 
        failed_attempts int(2) NOT NULL DEFAULT 0,
        name varchar(255) NOT NULL, 
        role varchar(255) NOT NULL, 
        PRIMARY KEY (id), 
        INDEX (id), 
        UNIQUE INDEX (email)
    );
");