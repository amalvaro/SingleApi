<?php

    include "Route.php";

    class ApiManager
    {

        /** @var string */
        private const PHP_FILE_EXTENSION    = ".php";

        /* Config branch */
        private const BRANCH_CONFIG         = "Config";
        private const BRANCH_CONFIG_DIR     = "ControllerDir";

        /* Controllers branch */
        private const BRANCH_CONTROLLER     = "Controllers";

        /** @var array */
        private $apiConfiguration;

        public function __construct($apiConfiguration) {
            $this->apiConfiguration = $apiConfiguration;
        }

        private function includeControllerClass($path, $name) {
            include $path.$name.$this::PHP_FILE_EXTENSION;
        }

        public function findRouteAndExecute($controller, $method, $args) {

            $this->includeControllerClass(
                $this->apiConfiguration[$this::BRANCH_CONFIG][$this::BRANCH_CONFIG_DIR],
                $controller
            );

            $controller = new $controller();
            return $controller->$method($args);

        }

    }