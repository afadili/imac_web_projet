<?php
require_once 'MyPDO.class.php';
// host=votre serveur (localhost si travail en local)
// note: charset utf8mb4 pour les emojis
$pdo = MyPDO::setConfiguration('mysql:localhost=mysql;dbname=emoji-tracker;charset=utf8mb4', 'root', '');
