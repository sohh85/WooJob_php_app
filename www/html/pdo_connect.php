<?php

$dsn = 'mysql:host=db;dbname=php_app_db;charset=utf8';
$user = 'php_learner';
$password = 'php_app';


try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    var_dump($dbh);
    echo "接続失敗:" . $e->getMessage();
    exit();
}
