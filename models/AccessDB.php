<?php
namespace MotoDB\models;

use PDO;

class AccessDB implements DB 
{
    private static $pdo;

    /**
     * AccessDB конструктор
     *
     * @param string $dsn название блока опций, прописанного в /etc/odbc.ini
     */
    public function __construct($dsn) 
    {
        if(!self::$pdo) {
            self::$pdo = self::connectToDB($dsn);
        }
    }

    /**
     * Функция для подключения к базе данных
     *
     * @param string $dsn название блока опций, прописанного в /etc/odbc.ini
     *
     * @return object объект, представляющий соединение с сервером базы данных
     */
    public static function connectToDB($dsn) 
    {
        self::$pdo = new PDO("odbc:$dsn");
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return self::$pdo;
    }

    /**
     * Функция возвращает массив данных из таблицы Тов
     *
     * @return array массив данных из таблицы Тов
     */
    public function getItems() 
    {
        return self::$pdo->query('SELECT catalog_code, code, name, shop_1, shop_2, shop_3, price_rub FROM q_item')->fetchAll();
    }
}