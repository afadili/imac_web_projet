<?php
require_once 'MyPDO.class.php';
/**
 * PDO Configuration
 * -----------------
 * host: localhost / 127.0.0.1
 * dbname: emoji-tracker, name of the database
 * charset: utf8mb4 to enable extended unicode (emojis)
 * user: 'root' for local dev 'phpmyadmin' on PROD
 * password: secret ;)
 */
$pdo = MyPDO::setConfiguration('mysql:127.0.0.1=mysql;dbname=emoji-tracker;charset=utf8mb4', 'root', '');
