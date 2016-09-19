<?php
    namespace MotoDB;

    /*
    Задание:
    Вытяжка товаров из access'a
    Проверка на наличие в sql базе по полю t_item.code
    Добавление в sql базу новых товаров с пустым полем checked_on
    Добавление и обновление остатков по товарам и магазинам из access'a в sql базу в t_item_shop
    */

    ini_set('max_execution_time', 0);
    ini_set('log_errors', 'On');
    ini_set('error_log', 'php_errors.log');
   
    require 'vendor\autoload.php';
    $config = require 'config.php';

    use MotoDB\mySqlDB as mySqlDB;
    use MotoDB\AccessDB as AccessDB;

    $Access = new AccessDB($config);
    $mySql  = new mySqlDB($config);

    foreach ($Access->getItems() as $item) {
        $mySql->getFromDB($item);
    }
    echo "\nI'm done!";