<?php
    class IdentificationController {

        private $e;

        public function __construct($i)
        {
            $this->e = $i;
        }

        public function Register($i, $login, $password) {
            return array("dependency1" => $i, "dependency2" => $this->e, "login" => $login, "password" => $password);
        }
    }