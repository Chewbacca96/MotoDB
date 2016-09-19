<?php
    namespace MotoDB;

    class AccessDB {
        static private $pdo;

        function __construct($config) {
            if(!self::$pdo) {
                self::$pdo = DB::connectToAccess($config['accessOpt']);
            }
        }

        public function getItems() {
            return self::$pdo->query('SELECT catalog_code, code, name, shop_1, shop_2, shop_3, price_rub FROM q_item')->fetchAll();
        }
    }