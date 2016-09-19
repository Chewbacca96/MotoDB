<?php
    namespace MotoDB;

    class DB {
        static private $pdoMySQL;
        static private $pdoAccess;

        static public function connectToMySQL($dbOptions) {
            if (self::$pdoMySQL) {
                return self::$pdoMySQL;
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

            return self::$pdoMySQL = new \PDO($dsn, $user, $pass, $options);
        }

        static public function connectToAccess($dbOptions) {
            if (self::$pdoAccess) {
                return self::$pdoAccess;
            }

            $driver = $dbOptions['driver'];
            $db     = $dbOptions['db'];
            $user   = $dbOptions['user'];
            $pass   = $dbOptions['pass'];

            self::$pdoAccess = new \PDO("odbc:Driver=$driver;Dbq=$db;Uid=$user;Pwd=$pass");
            self::$pdoAccess->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$pdoAccess->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

            return self::$pdoAccess;
        }
    }    