<?php
    namespace MotoDB\exceptions;

    class PdoException extends \Exception implements CustomException{
        public function errorLog() {
            return error_log('Error: ' . $this->getMessage());
        }
    }