<?php

    include_once "util/response/Response.php";

    class IdentificationController {

        private $e;

        public function __construct($i)
        {
            $this->e = $i;
        }

        public function Register($i, $login, $password) {
            return new FailResponse("Bad login");
            // return new FailResponse();
            // return new SuccessResponse();
            // return new Response(E_RESPONSE::SUCCESS, "Data", "Message to client");
        }
    }