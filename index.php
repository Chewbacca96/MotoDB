<?php
    namespace MotoDB;

    /*
    Задание:
    Вытяжка товаров из Access'a
    Проверка на наличие в MySql базе по полю t_item.code
    Добавление в MySql базу новых товаров с пустым полем checked_on
    Добавление и обновление остатков по товарам и магазинам из Access'a в MySql базу в t_item_shop
    */

    require 'vendor\autoload.php';
    $config = require 'config.php';

    ini_set('max_execution_time', 0);
    ini_set('log_errors', 'On');
    ini_set('error_log', $config['errorLog']);

    use MotoDB\AccessDB as AccessDB;
    use MotoDB\Item as Item;
    use MotoDB\Shop as Shop;

    $Access = new AccessDB($config);
    $Items  = new Item($config);
    $Shops  = new Shop($config);

    foreach ($Access->getItems() as $item) {
        try {
            $item['id'] = $Items->getFromDB($item, $config['is_append_new_items']);

            $Shops->getFromDB($item, $config['is_append_new_items']);
        } catch(\PDOException $pdoExsp) {
        } catch(DataException $exsp) {
            $exsp->errorLog();
        } 
    }

    echo "\nI'm done!";