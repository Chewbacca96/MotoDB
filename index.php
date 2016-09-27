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

    use MotoDB\models\AccessDB;
    use MotoDB\models\Item;
    use MotoDB\models\Shop;
    use MotoDB\exceptions\PdoException;
    use MotoDB\exceptions\DataException;

    $Access = new AccessDB($config);
    $Items  = new Item($config);
    $Shops  = new Shop($config);

    foreach ($Access->getItems() as $item) {
        try {
            $item['id'] = $Items->getFromDB($item, $config['isAppendNewItems']);

            $Shops->getFromDB($item, $config['isAppendNewItems']);
        } catch(PdoException $e) {
            $e->errorLog();
        } catch(DataException $e) {
            $e->errorLog();
        } 
    }

    echo "\nI'm done!";