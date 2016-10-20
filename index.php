<?php
namespace MotoDB;

use MotoDB\models\AccessDB;
use MotoDB\models\Item;
use MotoDB\models\Shop;
use MotoDB\exceptions\DataException;

require 'vendor/autoload.php';
$config = require (isset($argv[1])) ? $argv[1] : 'config.php';

ini_set('max_execution_time', 0);
ini_set('log_errors', 'On');
ini_set('error_log', $config['errorLog']);
date_default_timezone_set('Europe/Moscow');

/**
 * Задание:
 * Вытяжка товаров из Access'a
 * Проверка на наличие в MySql базе по полю t_item.code
 * Добавление в MySql базу новых товаров с пустым полем checked_on
 * Добавление и обновление остатков по товарам и магазинам из Access'a в MySql базу в t_item_shop
 */

$Access = new AccessDB($config['accessOpt']['dsn']);
$Items  = new Item($config['sqlOpt']);
$Shops  = new Shop($config['sqlOpt']);

foreach ($Access->getItems() as $item) {
    try {
        $item['id'] = $Items->getFromDB($item, $config['isAppendNewItems']);

        if ($item['id']) {
            $Shops->setToDB(
                $item['id'], 
                $item['shop_1'], 
                $item['shop_2'], 
                $item['shop_2']
            );
        }
    } catch(\PDOException $e) {
        error_log('Error: ' . $e->getMessage());
    } catch(DataException $e) {
        error_log('Error: ' . $e->getMessage());
    } 
}

echo "\nI'm done!\n";