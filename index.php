<?php
    namespace MotoDB;

    /*
    Задание:
    Вытяжка товаров из access'a
    Проверка на наличие в sql базе по полю t_item.code
    Добавление в sql базу новых товаров с пустым полем checked_on
    Добавление и обновление остатков по товарам и магазинам из access'a в sql базу в t_item_shop
    */

    //Сделать интерфейс DB и иимплементировать его в виде классов

    ini_set('max_execution_time', 0);
    ini_set('log_errors', 'On');
    ini_set('error_log', 'php_errors.log');
   
    require 'vendor\autoload.php';
    $config = require 'config.php';

    use MotoDB\AccessDB as AccessDB;
    use MotoDB\Item as Item;
    use MotoDB\Shop as Shop;

    $Access = new AccessDB($config);
    $Items   = new Item($config);
    $Shops   = new Shop($config);

    foreach ($Access->getItems() as $item) {
        $Items->getFromDB($item);

        if ($item['shop_1'] > 0) {
            $Shops->getFromDB($item, 1);
        }

        if ($item['shop_2'] > 0) {
            $Shops->getFromDB($item, 2);
        }

        if ($item['shop_3'] > 0) {
            $Shops->getFromDB($item, 3);
        }

        /*$enc = mb_detect_encoding($item['name']);
        echo mb_convert_encoding($item['name'], $enc, 'windows-1251') . "\n";*/
    }
    echo "\nI'm done!";