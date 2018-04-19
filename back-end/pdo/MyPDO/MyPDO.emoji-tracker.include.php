<?php
require_once 'MyPDO.class.php';

// host=votre serveur (localhost si travail en local)
$pdo = MyPDO::setConfiguration('mysql:localhost=mysql;dbname=emoji-tracker;charset=utf8', 'root', '');
