<?php
    namespace MotoDB;

    class mySqlDB {
        static private $pdo;
        static private $itemFromDB = [];

        function __construct($config) {
            if(!self::$pdo) {
                self::$pdo = DB::connectToMySQL($config['sqlOpt']);
            }
        }

        public function setToDB($item) {
            $stmt = self::$pdo->prepare('INSERT INTO motodb2.t_item_copy (category_id, code, name, price) VALUES (?, ?, ?, ?)');
            $stmt->execute([$item['catalog_code'], $item['code'], utf8_encode($item['name']), $item['price_rub']]);
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