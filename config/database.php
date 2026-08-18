<?php
/**
 * Database connection config.
 * Default values match a fresh XAMPP install (root user, no password).
 * If you set a MySQL root password, update DB_PASS below.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'smart_portal');
define('DB_USER', 'root');
define('DB_PASS', '');

function getDbConnection(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // real prepared statements
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // In a real deployment, log this instead of echoing it.
            die('Database connection failed. Make sure MySQL is running in XAMPP '
                . 'and that the "smart_portal" database has been imported. ('
                . $e->getMessage() . ')');
        }
    }

    return $pdo;
}
