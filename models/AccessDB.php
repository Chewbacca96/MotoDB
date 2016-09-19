<?php
    namespace MotoDB;

    class AccessDB {
        static private $pdo;

        function __construct($config) {
            if(!self::$pdo) {
                self::$pdo = DB::connectToAccess($config['accessOpt']);
            }
        }

        public function residue() {
            $shopResidue[1] = self::$pdo->query('SELECT code FROM q_item WHERE shop_1 > 0')->fetchAll(\PDO::FETCH_COLUMN);
            $shopResidue[2] = self::$pdo->query('SELECT code FROM q_item WHERE shop_2 > 0')->fetchAll(\PDO::FETCH_COLUMN);
            $shopResidue[3] = self::$pdo->query('SELECT code FROM q_item WHERE shop_3 > 0')->fetchAll(\PDO::FETCH_COLUMN);
            return $shopResidue;
        }

        public function getItems() {
            return self::$pdo->query('SELECT catalog_code, code, name, price_rub FROM q_item')->fetchAll();
        }
    }