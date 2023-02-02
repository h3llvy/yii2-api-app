<?php

$host = @$_ENV['DB_HOST'];
$dbname = @$_ENV['DB_NAME'];
$port = @$_ENV['DB_PORT'];
$username = @$_ENV['DB_USERNAME'];
$password = @$_ENV['DB_PASSWORD'];

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host={$host};dbname={$dbname};port={$port}",
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8mb4',
];
