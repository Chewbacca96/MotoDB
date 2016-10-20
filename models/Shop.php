<?php
namespace MotoDB\models;

use MotoDB\exceptions\DataException;

class Shop 
{
    private static $pdo;
    private static $id;

    /**
     * Shop конструктор
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
     * Фцнуция добавления данных в таблицу t_item_shop
     *
     * @param int id товара из таблицы t_item
     * @param int $shop_1 остаток товара в 1 магазине
     * @param int $shop_2 остаток товара в 2 магазине
     * @param int $shop_3 остаток товара в 3 магазине
     *
     * @return int id добавленной записи
     */
    public function setToDB($id, $shop_1, $shop_2, $shop_3) 
    {
        if ($id < 1 || $id == null) {
            throw new DataException('Data in the id field may not be less than 1 or null.');
        } elseif ($shop_1 < 0 || $shop_1 = null) {
            throw new DataException('Data in the shop_1 field may not be less than 0 or null.');
        } elseif ($shop_2 < 0 || $shop_2 = null) {
            throw new DataException('Data in the shop_2 field may not be less than 0 or null.');
        } elseif ($shop_3 < 0 || $shop_3 = null) {
            throw new DataException('Data in the shop_3 field may not be less than 0 or null.');
        }

        $stmt = self::$pdo->prepare('INSERT INTO motodb2.t_item_shop (shop_id, item_id, count)
            VALUES (:shopID1, :itemID, :count1), (:shopID2, :itemID, :count2), (:shopID3, :itemID, :count3) 
            ON DUPLICATE KEY UPDATE count = VALUES(count)');
        
        $stmt->execute([
            'shopID1' => 1,
            'shopID2' => 2,
            'shopID3' => 3,
            'itemID'  => $id,
            'count1'  => $shop_1,
            'count2'  => $shop_2,
            'count3'  => $shop_3
        ]);
        
        return self::$pdo->lastInsertId();
    }
}