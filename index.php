<?php
    namespace MotoDB;

    /*
    Задание:
    Вытяжка товаров из Access'a
    Проверка на наличие в MySql базе по полю t_item.code
    Добавление в MySql базу новых товаров с пустым полем checked_on
    Добавление и обновление остатков по товарам и магазинам из Access'a в MySql базу в t_item_shop
    */
        
    //файл .sql должен содержать код только для новых полей
    //каждый класс работает со своей моделью данных
    //соответствие полей (бизнес логика) в основном файле
    //добавить проверок и логов для автономной работы

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
        $item['id'] = $Items->getFromDB($item);

        $Shops->setToDB($item);
    }

    echo "\nI'm done!";