<?php
    namespace MotoDB;

    class DB {
        static private $pdo;

        static public function connectToSQL($dbOptions) {
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

            return new \PDO($dsn, $user, $pass, $options);
        }

        static public function connectToAccess($dbOptions) {
            if (self::$pdo) {
                return self::$pdo;
            }

            $db   = $dbOptions['db'];
            $user = $dbOptions['user'];
            $pass = $dbOptions['pass'];

            self::$pdo = new \PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=$db;Uid=$user;Pwd=$pass");
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

            return self::$pdo;
        }
    }    