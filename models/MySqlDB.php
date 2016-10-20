<?php
namespace MotoDB\models;

use PDO;

class MySqlDB implements DB 
{
    private static $pdo;

    /**
     * Функция подключения к MySql базе данных 
     *
     * @param array $dbOptions массив опций для подключения к MySql базе данных
     *
     * @return object объект, представляющий соединение с сервером базы данных
     */
    public static function connectToDB($dbOptions) 
    {
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
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        return self::$pdo = new PDO($dsn, $user, $pass, $options);
    }
}