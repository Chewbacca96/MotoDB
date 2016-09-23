<?php
    namespace MotoDB;

    class AccessDB implements DB {
        static private $pdo;

        function __construct($config) {
            if(!self::$pdo) {
                self::$pdo = self::connectToDB($config['accessOpt']);
            }
        }

        static public function connectToDB($dbOptions) {
            if (self::$pdo) {
                return self::$pdo;
            }

            $driver = $dbOptions['driver'];
            $db     = $dbOptions['db'];
            $user   = $dbOptions['user'];
            $pass   = $dbOptions['pass'];

            self::$pdo = new \PDO("odbc:Driver=$driver;Dbq=$db;Uid=$user;Pwd=$pass");
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

            return self::$pdo;
        }

        public function getItems() {
            return self::$pdo->query('SELECT catalog_code, code, name, shop_1, shop_2, shop_3, price_rub FROM q_item')->fetchAll();
        }
    }