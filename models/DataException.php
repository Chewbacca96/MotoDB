<?php
    namespace MotoDB;

    class DataException extends \Exception {
        function __construct($message, $code = 0, \Exception $previous = null) {
            parent::__construct($message, $code, $previous);
        }

        public function errorLog() {
            error_log('Error: ' . $this->getMessage(), 0);
            return null;
        }
    }