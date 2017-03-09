<?php

$host = "127.0.0.1";
$user = "user";
$pwd = "pass";
$dbn = 'konomi';

$msg_connect = "Verbindung zur Datenbank konnte nicht hergestellt werden";
$msg_db = "Datenbank konnte nicht ausgewählt werden";

$db = mysqli_connect($host, $user, $pwd) or die($msg_connect);
mysqli_select_db($db, $dbn) or die ($msg_db);
?>