<?php
    namespace MotoDB;

    class DataException extends \Exception {
        public function errorLog() {
            return error_log('Error: ' . $this->getMessage(), 0);
        }
    }