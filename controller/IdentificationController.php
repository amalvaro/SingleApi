<?php
    class IdentificationController {
        public function Register($login, $password) {
            return array("login" => $login, "password" => $password);
        }
    }