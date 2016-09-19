<?php
    namespace MotoDB;

    interface DB {
        public function connectToDB($dbOptions);
    }