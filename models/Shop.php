<?php
    namespace MotoDB;

    class Shop {
        static private $pdo;
        static private $itemFromDB = [];
        static private $itemFromShop = [];

        function __construct($config) {
            if(!self::$pdo) {
                self::$pdo = DB::connectToMySQL($config['sqlOpt']);
            }
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
                self::$itemFromShop = $stmt;
            } elseif ($append) {
                self::$itemFromShop = $this->setToDB($item, $shopID);
            } else {
                self::$itemFromShop = null;
            }
            
            return self::$itemFromShop;
        }
    }