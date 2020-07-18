<?php

    class E_RESPONSE {
        public const SUCCESS    = 1;
        public const FAIL       = 0;
    }


    class Response implements JsonSerializable
    {
        /** @var int */
        private $ResponseCode;

        /** @var string */
        private  $ResponseData;

        /** @var string */
        private $ResponseMessage;

        public function __construct($ResponseCode, $ResponseData = null, $ResponseMessage = null)
        {
            $this->ResponseCode = $ResponseCode;
            $this->ResponseData = $ResponseData;
            $this->ResponseMessage = $ResponseMessage;
        }

        /**
         * @inheritDoc
         */
        public function jsonSerialize()
        {
            $responses = array();

            if($this->ResponseCode != null)
                $responses["ResponseCode"] = $this->ResponseCode;

            if($this->ResponseData != null)
                $responses["ResponseData"] = $this->ResponseData;

            if($this->ResponseMessage != null)
                $responses["ResponseMessage"] = $this->ResponseMessage;

            return $responses;

        }
    }

    class SuccessResponse extends Response {
        public function __construct()
        {
            parent::__construct(E_RESPONSE::SUCCESS);
        }
    }

    class FailResponse extends Response {
        public function __construct()
        {
            parent::__construct(E_RESPONSE::FAIL);
        }
    }



