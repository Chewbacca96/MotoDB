<?php
    namespace MotoDB;

    class Item implements DB{
        static private $pdo;
        static private $itemFromDB = [];

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

        public function setToDB($item) {
            $encoding = mb_detect_encoding($item['name']);
            $item['name'] = mb_convert_encoding($item['name'], $encoding, 'windows-1251');

            $stmt = self::$pdo->prepare('INSERT INTO motodb2.t_item_copy (category_id, code, name, price, old_price) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$item['catalog_code'], $item['code'], $item['name'], $item['price_rub'], 0]);

            return self::$pdo->lastInsertId();
        }

        public function getFromDB($item, $append = true) {
            if (!array_key_exists($item['code'], self::$itemFromDB)) {
                $stmt = self::$pdo->prepare('SELECT id FROM motodb2.t_item_copy WHERE code = ?');
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