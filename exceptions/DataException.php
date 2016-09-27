<?php
    namespace MotoDB\exceptions;

    class DataException extends \Exception implements CustomException{
        public function errorLog() {
            return error_log('Error: ' . $this->getMessage());
        }
    }