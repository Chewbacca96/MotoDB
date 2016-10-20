<?php
namespace MotoDB\models;

use MotoDB\exceptions\DataException;

class Item 
{
    private static $pdo;
    private static $itemFromDB = [];

    /**
     * Item конструктор
     * 
     * @param array $dbOptions массив опций для подключения к MySql базе данных 
     */
    public function __construct($dbOptions) 
    {
        if(!self::$pdo) {
            self::$pdo = MySqlDB::connectToDB($dbOptions);
        }
    }

    /**
     * Фцнуция добавления данных в таблицу t_item
     *
     * @param array $item массив данных о товаре
     *
     * @return int id добавленной записи
     */
    public function setToDB($item) 
    {
        if ($item['code'] < 1 || $item['code'] == null) {
            throw new DataException('Data in the code field may not be less than 1 or null.');
        } elseif ($item['catalog_code'] < 1 || $item['catalog_code'] == null) {
            throw new DataException('Data in the catalog_code field may not be less than 1 or null.');
        } elseif ($item['price_rub'] < 0 || $item['price_rub'] == null) {
            throw new DataException('Data in the price_rub field may not be less than 0 or null.');
        }

        $item['name'] = mb_convert_encoding($item['name'], 'utf-8', 'windows-1251');

        $stmt = self::$pdo->prepare('INSERT INTO motodb2.t_item (category_id, code, name, price, old_price) VALUES (?, ?, ?, ?, ?)');

        $stmt->execute([
            $item['catalog_code'], 
            $item['code'], 
            $item['name'], 
            $item['price_rub'], 
            0
        ]);

        return self::$pdo->lastInsertId();
    }

    /**
     * Функция возвращает id товара по коду товара
     *
     * @param array $item массив данных о товаре
     * @param boolean $append 
     *
     * @return int id добавленной записи
     */
    public function getFromDB($item, $append = true) 
    {
        if (!array_key_exists($item['code'], self::$itemFromDB)) {
            $stmt = self::$pdo->prepare('SELECT id FROM motodb2.t_item WHERE code = ?');
            $stmt->execute([$item['code']]);
            $stmt = $stmt->fetchColumn();

            if ($stmt) {
                self::$itemFromDB[$item['code']] = $stmt;
            } elseif ($append) {
                self::$itemFromDB[$item['code']] = $this->setToDB($item);
            } else {
                self::$itemFromDB[$item['code']] = null;
            }
        }

        return self::$itemFromDB[$item['code']];
    }
}