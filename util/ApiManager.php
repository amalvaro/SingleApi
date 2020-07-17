<?php

    assert_options(ASSERT_BAIL, true);

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
        private const METHOD_PARAMS         = "ParamCollection";
        private const PARAM_COUNT           = "ParamCount";

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

        public function getMethodParams($controllerName, $method) {
            $method = $this->getMethod($controllerName, $method);
            return $method[$this::METHOD_PARAMS];
        }

        public function getMethodParamCount($controllerName, $method) {
            $method = $this->getMethod($controllerName, $method);
            return $method[$this::PARAM_COUNT];
        }

        private function verifyParameters($controller, $method, $arguments) {
            $methodParams = $this->getMethodParams($controller, $method);
            $paramsCount = $this->getMethodParamCount($controller, $method);

            assert(count($arguments) == $paramsCount);

            for($i = 0; $i < count($arguments); $i++) {
                $arg = $arguments[$i];
                if(isset($methodParams[$i])) {
                    $keys = array_keys($methodParams[$i]);
                    foreach ($keys as $key) {
                        $condition = $methodParams[$i][$key];
                        switch ($key) {
                            case "Type": {
                                if($condition == "number")
                                    assert(is_numeric($arg));
                                break;
                            }
                            case "Expression": {
                                assert(preg_match($condition, $arg) == 1);
                                break;
                            }
                            case "MinLength": {
                                assert(mb_strlen($arg, "UTF-8") >= $condition);
                                break;
                            }
                            case "MaxLength": {
                                assert(mb_strlen($arg, "UTF-8") <= $condition);
                                break;
                            }
                            case "Min": {
                                assert(intval($arg) >= $condition);
                                break;
                            }
                            case "Max": {
                                assert(intval($arg) <= $condition);
                                break;
                            }
                            case "Action": {
                                assert($condition());
                                break;
                            }
                        }
                    }
                }
            }
        }

        public function findRouteAndExecute($controller, $method, $args) {

            if(isset($this->apiConfiguration[$this::BRANCH_CONTROLLER][$controller][$this::BRANCH_METHOD][$method])) {
                $this->includeControllerClass(
                    $this->apiConfiguration[$this::BRANCH_CONFIG][$this::BRANCH_CONFIG_DIR],
                    $controller
                );
            }

            $this->verifyParameters($controller, $method, $args);

            $controller = new $controller();
            return $controller->$method(...$args);

        }

    }