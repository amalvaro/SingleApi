<?php


    // Query format: IdentificationController::Register?login&password
    // Token var: token
    // Post var: route

    class ParserStatic {
        // COMMON MAGIC's
        public const QUERY_MASK             = "/(\w*)::(\w*)\?(.*)/m";
        public  const ROUTE_ASSOC_NAME      = "route";
        public  const TOKEN_ASSOC_NAME      = "token";
        public  const ROUTE_ARGS_DELIMITER  = "&";
    }

    class QueryStatic {
        public  const CONTROLLER_INDEX  = 1;
        public  const METHOD_INDEX      = 2;
        public  const ARGUMENTS_INDEX   = 3;
    }

    class ApiQueryParser
    {


        /** @var array */
        private $postRequest;

        /** @var array */
        private $parseCache;

        /** @var array */
        private $argumentCache;

        public function __construct($postRequest) {
            $this->postRequest = $postRequest;
            $this->parseQuery();
        }

        private function parseQuery() {

            if(!$this->parseCache)
                return preg_match(
                    ParserStatic::QUERY_MASK,
                    $this->postRequest[ParserStatic::ROUTE_ASSOC_NAME],
                    $this->parseCache
                );

            return $this->parseCache;
        }

        public function resetCache() {
            $this->parseQuery();
        }

        public function getControllerName() {
            return $this->parseCache[QueryStatic::CONTROLLER_INDEX];
        }

        public function getMethodName() {
            return $this->parseCache[QueryStatic::METHOD_INDEX];
        }

        public function isQueryValid() {
            return isset($this->postRequest["route"]) ? true : false;
        }

        public function getToken() {
            return isset($this->postRequest[ParserStatic::TOKEN_ASSOC_NAME]) ?
                $this->postRequest[ParserStatic::TOKEN_ASSOC_NAME] : false;
        }

        public function getArgs() {
            if(!$this->argumentCache)
                $this->argumentCache = explode(ParserStatic::ROUTE_ARGS_DELIMITER,
                    $this->parseCache[QueryStatic::ARGUMENTS_INDEX]);

            return $this->argumentCache;
        }

    }