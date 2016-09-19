<?php
    namespace MotoDB;

    class Shop implements DB{
        static private $pdo;
        static private $itemFromShop = [];

        function __construct($config) {
            if(!self::$pdo) {
                self::$pdo = $this->connectToDB($config['sqlOpt']);
            }
        }

        public function connectToDB($dbOptions) {
            if (self::$pdo) {
                return self::$pdo;
            }

            $host = $dbOptions['host'];
            $db   = $dbOptions['db'];
            $user = $dbOptions['user'];
            $pass = $dbOptions['pass'];

            $dsn = "mysql:host = $host; dbname = $db";
            $options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ];

            return self::$pdo = new \PDO($dsn, $user, $pass, $options);
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