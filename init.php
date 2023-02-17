<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

$user = 'user';
$pass = '';
$dsn  = 'mysql:dbname=test;host=localhost;charset=utf8mb4';
$opt  = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
);
$pdo = new PDO($dsn, $user, $pass, $opt);
