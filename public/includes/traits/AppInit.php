<?php

namespace traits;

trait AppInit
{
    public static function createTables(): void
    {
        self::$db->query("CREATE TABLE IF NOT EXISTS users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(255) NOT NULL
        )");

        self::$db->query("CREATE TABLE IF NOT EXISTS organizations (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        self::$db->query("CREATE TABLE IF NOT EXISTS properties (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            country VARCHAR(255) NOT NULL,
            postal_code VARCHAR(255) NOT NULL,
            city VARCHAR(255) NOT NULL,
            address VARCHAR(255) NOT NULL
        )");

        self::$db->query("CREATE TABLE IF NOT EXISTS tokens (
            token varchar(255) UNIQUE NOT NULL,
            user_id INT(16) UNSIGNED UNIQUE NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )");


        /*Relation tables*/
        self::$db->query("CREATE TABLE IF NOT EXISTS user_organization_relations (
            user_id INT UNSIGNED NOT NULL,
            organization_id INT UNSIGNED NOT NULL
        )");

        self::$db->query("CREATE TABLE IF NOT EXISTS organization_property_relations (
            organization_id INT UNSIGNED NOT NULL,
            property_id INT UNSIGNED NOT NULL
        )");
    }
}
