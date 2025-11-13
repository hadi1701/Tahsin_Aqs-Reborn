<?php
const DB_HOST = "localhost";    // DB Server IP Addres
const DB_NAME = "tahsin";          // Database / Schema Name
const DB_USER = "ppkpipos1";     // Database User
const DB_PASS = "ppkpipos1";     // Database Password

function db(): PDO
{
    static $pdo;
    if (!$pdo) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}