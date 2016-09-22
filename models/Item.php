<?php
    namespace MotoDB;

    class Item {
        static private $pdo;
        static private $itemFromDB = [];

        function __construct($config) {
            if(!self::$pdo) {
                self::$pdo = MySqlDB::connectToDB($config['sqlOpt']);
            }
        }

        public function setToDB($item) {
            if ($item['code'] < 1 || $item['code'] == null) {
                error_log('Data in the code field may not be less than 1 or null.', 0);
                exit();
            } elseif ($item['catalog_code'] < 1 || $item['catalog_code'] == null) {
                error_log('Data in the catalog_code field may not be less than 1 or null.', 0);
                exit();
            } elseif ($item['price_rub'] < 0 || $item['price_rub'] == null) {
                error_log('Data in the price_rub field may not be less than 0 or null.', 0);
                exit();
            }

            $item['name'] = mb_convert_encoding($item['name'], 'utf-8', 'windows-1251');

            $stmt = self::$pdo->prepare('INSERT INTO motodb2.t_item_copy (category_id, code, name, price, old_price) VALUES (?, ?, ?, ?, ?)');
            $stmt = $stmt->execute([$item['catalog_code'], $item['code'], $item['name'], $item['price_rub'], 0]);
            
            if (!$stmt) {
                error_log('Item::setToDB - cant set data to DB.', 0);
                exit();
            }

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