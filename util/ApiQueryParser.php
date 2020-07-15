<?php


    // Query format: IdentificationController::Register?login&password
    // Post var: route

    class ApiQueryParser
    {
        // COMMON MAGIC's
        private const QUERY_MASK            = "'/(\w*)::(\w*)\?(.*)/m'";
        private const ROUTE_ASSOC_NAME      = "route";
        private const ROUTE_ARGS_DELIMITER  = "&";

        // QUERY REGION OFFSET
        private const CONTROLLER_INDEX  = 1;
        private const METHOD_INDEX      = 2;
        private const ARGUMENTS_INDEX   = 3;

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
                    $this::QUERY_MASK,
                    $this->postRequest[$this::ROUTE_ASSOC_NAME],
                    $this->parseCache,
                    PREG_SET_ORDER, 0
                );

            return $this->parseCache;
        }

        public function resetCache() {
            $this->parseQuery();
        }

        public function getControllerName() {
            return $this->parseCache[$this::CONTROLLER_INDEX];
        }

        public function getMethodName() {
            return $this->parseCache[$this::METHOD_INDEX];
        }

        public function getArgs() {
            if(!$this->argumentCache)
                $this->argumentCache = explode($this::ROUTE_ARGS_DELIMITER, $this->parseCache[$this::ARGUMENTS_INDEX]);

            return $this->argumentCache;
        }

    }