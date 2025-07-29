<?php
global $db;
$db->exec("
    CREATE TABLE password_resets (
        user_id int(10) NOT NULL, 
        token varchar(255) NOT NULL UNIQUE, 
        expires_at datetime NOT NULL, 
        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (user_id), 
        INDEX (token)
    );
");

$db->exec("
    ALTER TABLE password_resets 
        ADD CONSTRAINT FKpassword_r969087 
            FOREIGN KEY (user_id) 
                REFERENCES users (id) 
                ON DELETE Cascade;

");