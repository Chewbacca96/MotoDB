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

        public function setToDB($item) {
            if ($item['id'] < 1 || $item['id'] == null) {
                throw new DataException('Data in the id field may not be less than 1 or null.');
            } elseif ($item["shop_1"] < 0 || $item["shop_1"] = null) {
                throw new DataException('Data in the shop_1 field may not be less than 0 or null.');
            } elseif ($item["shop_2"] < 0 || $item["shop_2"] = null) {
                throw new DataException('Data in the shop_2 field may not be less than 0 or null.');
            } elseif ($item["shop_3"] < 0 || $item["shop_3"] = null) {
                throw new DataException('Data in the shop_3 field may not be less than 0 or null.');
            }

            $stmt = self::$pdo->prepare('INSERT INTO motodb2.t_item_shop (shop_id, item_id, count)
                VALUES (:shopID1, :itemID, :count1), (:shopID2, :itemID, :count2), (:shopID3, :itemID, :count3) 
                ON DUPLICATE KEY UPDATE count = VALUES(count)');
            $stmt->execute([
                'shopID1' => 1,
                'shopID2' => 2,
                'shopID3' => 3,
                'itemID'  => $item['id'],
                'count1'  => $item["shop_1"],
                'count2'  => $item["shop_2"],
                'count3'  => $item["shop_3"]
            ]);
            
            return self::$pdo->lastInsertId();
        }

        public function getFromDB($item, $append = true) {
            $stmt = self::$pdo->prepare('SELECT id from motodb2.t_item_shop WHERE id = ?');
            $stmt->execute([$item['id']]);
            $stmt = $stmt->fetchColumn();

            if ($stmt) {
                self::$id = $stmt;
            } elseif ($append) {
                self::$id = $this->setToDB($item);
            } else {
                self::$id = null;
            }

            return self::$id;
        }
    }