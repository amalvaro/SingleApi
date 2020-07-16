<?php

    class ApiManager
    {

        /** @var string */
        private const PHP_FILE_EXTENSION    = ".php";

        /* Config branch */
        private const BRANCH_CONFIG         = "Config";
        private const BRANCH_CONFIG_DIR     = "ControllerDir";

        /* Controllers branch */
        private const BRANCH_CONTROLLER     = "Controllers";

        /* Methods branch */
        private const BRANCH_METHOD         = "Methods";
        private const METHOD_EXTERNAL       = "ExternalAccess";

        /** @var array */
        private $apiConfiguration;

        public function __construct($apiConfiguration) {
            $this->apiConfiguration = $apiConfiguration;
        }

        private function includeControllerClass($path, $name) {
            require_once $path.$name.$this::PHP_FILE_EXTENSION;
        }

        public function getMethod($controllerName, $method) {
            return $this->apiConfiguration[$this::BRANCH_CONTROLLER][$controllerName][$this::BRANCH_METHOD][$method];
        }

        public function getController($controllerName) {
            return $this->apiConfiguration[$this::BRANCH_CONTROLLER][$controllerName];
        }

        public function canExternal($controllerName, $method) {
            $method = $this->getMethod($controllerName, $method);
            return isset($method[$this::METHOD_EXTERNAL]) ? $method[$this::METHOD_EXTERNAL] : false;
        }

        public function findRouteAndExecute($controller, $method, $args) {

            if(isset($this->apiConfiguration[$this::BRANCH_CONTROLLER][$controller][$this::BRANCH_METHOD][$method])) {
                $this->includeControllerClass(
                    $this->apiConfiguration[$this::BRANCH_CONFIG][$this::BRANCH_CONFIG_DIR],
                    $controller
                );
            }

            $controller = new $controller();
            return $controller->$method(...$args);
        }

    }