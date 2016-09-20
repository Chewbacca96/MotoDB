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

        public function updateToDB($item, $shopID) {
            $stmt = self::$pdo->prepare('UPDATE motodb2.t_item_shop SET count = ? WHERE code = ? AND shop_id = ?');
            return $stmt->execute([$item["shop_$shopID"], $item['code'], $shopID]);
        }

        public function setToDB($item, $shopID) {
            $stmt = self::$pdo->prepare('INSERT INTO motodb2.t_item_shop (shop_id, code, count) VALUES (?, ?, ?)');
            $stmt->execute([$shopID, $item['code'], $item["shop_$shopID"]]);

            return self::$pdo->lastInsertId();
        }

        public function getFromDB($item, $shopID, $append = true) {
            $stmt = self::$pdo->prepare('SELECT id FROM motodb2.t_item_shop WHERE code = ? AND shop_id = ?');
            $stmt->execute([$item['code'], $shopID]);
            $stmt = $stmt->fetchColumn();

            if ($stmt) {
                self::$itemFromShop = $this->updateToDB($item, $shopID);
            } elseif ($append) {
                self::$itemFromShop = $this->setToDB($item, $shopID);
            } else {
                self::$itemFromShop = null;
            }

            return self::$itemFromShop;
        }

        /*public function setToDB($item) {
            $stmt = self::$pdo->prepare('INSERT INTO motodb2.t_item_shop (shop_id, code, count) 
                VALUES (:shopID1, :code, :count1), (:shopID2, :code, :count2), (:shopID3, :code, :count3)');
            $stmt->execute([
                'code'    => $item['code'],
                'shopID1' => 1,
                'shopID2' => 2,
                'shopID3' => 3,
                'count1'  => $item['shop_1'],
                'count2'  => $item['shop_2'],
                'count3'  => $item['shop_3']
            ]);

            return self::$pdo->lastInsertId();
        }

        public function getFromDB($item, $append = true) {
            $stmt = self::$pdo->prepare('SELECT id FROM motodb2.t_item_shop WHERE code = ?');
            $stmt->execute([$item['code']]);
            $stmt = $stmt->fetchColumn();

            if ($stmt) {
                self::$itemFromShop = $this->updateToDB($item, $shopID);
            } elseif ($append) {
                self::$itemFromShop = $this->setToDB($item);
            } else {
                self::$itemFromShop = null;
            }

            return self::$itemFromShop;
        }*/
    }