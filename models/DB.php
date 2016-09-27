<?php
    namespace MotoDB\models;

    interface DB {
        static public function connectToDB($dbOptions);
    }