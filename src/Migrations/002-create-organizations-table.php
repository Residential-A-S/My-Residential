<?php
global $db;
$db->exec("
    CREATE TABLE organizations (
        id int(10) NOT NULL AUTO_INCREMENT, 
        name varchar(255) NOT NULL, 
        created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, 
        PRIMARY KEY (id), 
        INDEX (id)
    );
");