<?php
    namespace MotoDB;

    class Item {
        static private $pdo;
        static private $itemFromDB = [];

        function __construct($config) {
            if(!self::$pdo) {
                self::$pdo = DB::connectToMySQL($config['sqlOpt']);
            }
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