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
            try {
                if ($item['code'] < 1 || $item['code'] == null) {
                    throw new DataException('Data in the code field may not be less than 1 or null.');
                } elseif ($item['catalog_code'] < 1 || $item['catalog_code'] == null) {
                    throw new DataException('Data in the catalog_code field may not be less than 1 or null.');
                } elseif ($item['price_rub'] < 0 || $item['price_rub'] == null) {
                    throw new DataException('Data in the price_rub field may not be less than 0 or null.');
                }

                $item['name'] = mb_convert_encoding($item['name'], 'utf-8', 'windows-1251');

                $stmt = self::$pdo->prepare('INSERT INTO motodb2.t_item_copy (category_id, code, name, price, old_price) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$item['catalog_code'], $item['code'], $item['name'], $item['price_rub'], 0]);

                $id = self::$pdo->lastInsertId();
            } catch(DataException $exsp) {
                $id = $exsp->errorLog();
            }

            return $id;
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