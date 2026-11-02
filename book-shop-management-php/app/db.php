<?php
$host=getenv('DB_HOST')?:'127.0.0.1';$db=getenv('DB_NAME')?:'bookshop';$user=getenv('DB_USER')?:'bookuser';$pass=getenv('DB_PASSWORD')?:'bookpass';
try{$pdo=new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4",$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);}
catch(PDOException $e){http_response_code(503);exit('Database unavailable. Check database configuration.');}
