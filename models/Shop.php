<?php
    namespace MotoDB;

    class Shop {
        static private $pdo;
        static private $itemFromShop = [];

        function __construct($config) {
            if(!self::$pdo) {
                self::$pdo = MySqlDB::connectToDB($config['sqlOpt']);
            }
        }

        /*public function updateToDB($item, $shopID) {
            $stmt = self::$pdo->prepare('UPDATE motodb2.t_item_shop SET count = ? WHERE item_id = ? AND shop_id = ?');
            return $stmt->execute([$item["shop_$shopID"], $item['id'], $shopID]);
        }

        public function setToDB($item, $shopID) {
            $stmt = self::$pdo->prepare('INSERT INTO motodb2.t_item_shop (shop_id, item_id, count)
                VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE count = VALUES(count)');
            $stmt->execute([$shopID, $item['id'], $item["shop_$shopID"]]);

            return self::$pdo->lastInsertId();
        }

        public function getFromDB($item, $shopID, $append = true) {
            $stmt = self::$pdo->prepare('SELECT id FROM motodb2.t_item_shop WHERE item_id = ? AND shop_id = ?');
            $stmt->execute([$item['id'], $shopID]);
            $stmt = $stmt->fetchColumn();

            if ($stmt) {
                $this->setToDB($item, $shopID);
                self::$itemFromShop = $stmt;
            } elseif ($append) {
                self::$itemFromShop = $this->setToDB($item, $shopID);
            } else {
                self::$itemFromShop = null;
            }

            return self::$itemFromShop;
        }*/

        public function setToDB($item) {
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
    }