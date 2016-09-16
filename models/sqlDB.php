<?php
    namespace MotoDB;

    class sqlDB {
        static private $pdo;

        function __construct($config) {
            if(!self::$pdo) {
                self::$pdo = DB::connectToDB($config['dbOpt']);
            }
        }

        public function setToDB() {
            $stmt = self::$pdo->prepare('INSERT INTO motodb2.t_item () VALUES ()');
            $stmt->execute([]);
            return self::$pdo->lastInsertId();
        }

        public function getFromDB($code) {

        }
    }