<?php
// 以下の AlchemyAPI API Key と MySQL の接続情報を自身の環境に併せて編集する

// AlchemyAPI API Key
$apikey = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

// MySQL
$hostname = '(hostname)';
$port = 3306;
$dbname = '(dbname)';
$username = '(username)';
$password = '(password)';


// ここは編集不要
$dsn = 'mysql:dbname='.$dbname.';host='.$hostname.';port='.$port;
?>
