<?php
    namespace MotoDB;

    interface DB {
        static public function connectToDB($dbOptions);
    }