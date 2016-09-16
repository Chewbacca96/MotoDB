<?php
   namespace MotoDB;

   //Задание:
   //Вытяжка товаров из access'a
   //Проверка на наличие в sql базе по полю t_item.code
   //Добавление в sql базу новых товаров с пустым полем checked_on
   //Добавление и обновление остатков по товарам и магазинам из access'a в sql базу в t_item_shop

   ini_set('max_execution_time', 0);
   ini_set('log_errors', 'On');
   ini_set('error_log', 'php_errors.log');
   
   require 'vendor\autoload.php';
   $config = require 'config.php';

   use MotoDB\sqlDB as sqlDB;

   $dbName = 'DB\moto\DB\Auto_dat.mdb';
   $pdo = new \PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=$dbName;Uid=;Pwd=;");
   $pdo->exec("set names utf8");
   
   $stmt = $pdo->query('SELECT Код FROM item WHERE Код = 952');
   print_r($stmt->fetchColumn());

   echo "\nI'm done!";