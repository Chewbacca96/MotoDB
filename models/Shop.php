<?php
    namespace MotoDB\models;

    use MotoDB\exceptions\DataException;

    class Shop {
        static private $pdo;
        static private $id;

        function __construct($config) {
            if(!self::$pdo) {
                self::$pdo = MySqlDB::connectToDB($config['sqlOpt']);
            }
        }

        public function setToDB($id, $shop_1, $shop_2, $shop_3) {
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