<?php
    namespace MotoDB;

    class MySqlDB implements DB {
        static private $pdo;

        static public function connectToDB($dbOptions) {
            if (self::$pdo) {
                return self::$pdo;
            }

            $host    = $dbOptions['host'];
            $db      = $dbOptions['db'];
            $charset = $dbOptions['charset'];
            $user    = $dbOptions['user'];
            $pass    = $dbOptions['pass'];

            $dsn = "mysql:host = $host; dbname = $db; charset=$charset";
            $options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ];

            return self::$pdo = new \PDO($dsn, $user, $pass, $options);
        }
    }