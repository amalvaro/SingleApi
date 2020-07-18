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
        private const BRANCH_CONTROLLER         = "Controllers";
        private const CONTROLLER_DEPENDENCY     = "Dependency";
        private const METHOD_DEPENDENCY         = "Dependency";

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
            return (isset($method[$this::METHOD_PARAMS]) ? $method[$this::METHOD_PARAMS] : array());
        }

        public function getMethodParamCount($controllerName, $method) {
            $method = $this->getMethod($controllerName, $method);
            return (isset($method[$this::PARAM_COUNT]) ? $method[$this::PARAM_COUNT] : 0);
        }

        private function verifyParameters($controller, $method, $arguments) {
            $methodParams = $this->getMethodParams($controller, $method);
            $paramsCount = $this->getMethodParamCount($controller, $method);

            $success = ($paramsCount == count($arguments));

            for($i = 0; $i < count($arguments); $i++) {

                if(!$success)
                    break;

                $arg = $arguments[$i];
                if(isset($methodParams[$i])) {
                    $keys = array_keys($methodParams[$i]);
                    foreach ($keys as $key) {
                        $condition = $methodParams[$i][$key];
                        switch ($key) {
                            case "Type": {
                                $success &= (($condition == "number") ? ((is_numeric($arg)) ? true : false) : true);
                                break;
                            }
                            case "Expression": {
                                /* assert(preg_match($condition, $arg) == 1); */
                                $success &= (preg_match($condition, $arg) == 1);
                                break;
                            }
                            case "MinLength": {
                                /* assert(mb_strlen($arg, "UTF-8") >= $condition); */
                                $success &= (mb_strlen($arg, "UTF-8") >= $condition);
                                break;
                            }
                            case "MaxLength": {
                                /* assert(mb_strlen($arg, "UTF-8") <= $condition); */
                                $success &= (mb_strlen($arg, "UTF-8") <= $condition);
                                break;
                            }
                            case "Min": {
                                /* assert(intval($arg) >= $condition); */
                                $success &= (intval($arg) >= $condition);
                                break;
                            }
                            case "Max": {
                                /* assert(intval($arg) <= $condition); */
                                $success &= (intval($arg) <= $condition);
                                break;
                            }
                            case "Action": {
                                $success &= $condition();
                                break;
                            }
                        }
                    }
                }
            }

            return $success;

        }

        private function getControllerDependency($controller) {
            $controller = $this->getController($controller);

            $instances = array();

            if(isset($controller[$this::CONTROLLER_DEPENDENCY]))
                $instances = $this->releaseDependencies($controller[$this::CONTROLLER_DEPENDENCY]);

            return $instances;
        }

        private function getMethodDependency($controller, $method) {
            $methodConf = $this->getMethod($controller, $method);

            $instances = array();

            if(isset($methodConf[$this::METHOD_DEPENDENCY]))
                $instances = $this->releaseDependencies($methodConf[$this::METHOD_DEPENDENCY]);

            return $instances;
        }

        private function releaseDependencies($dependencies) {
            $instances = array();
            if(isset($dependencies)) {
                $keys = array_keys($dependencies);
                foreach ($keys as $key) {
                    array_push($instances, $dependencies[$key]());
                }
            }
            return $instances;
        }

        public function findRouteAndExecute($controller, $method, $args) {

            if(isset($this->apiConfiguration[$this::BRANCH_CONTROLLER][$controller][$this::BRANCH_METHOD][$method])) {
                $this->includeControllerClass(
                    $this->apiConfiguration[$this::BRANCH_CONFIG][$this::BRANCH_CONFIG_DIR],
                    $controller
                );
            }

            if($this->verifyParameters($controller, $method, $args)) {
                $controllerInst = new $controller(...$this->getControllerDependency($controller));
                return $controllerInst->$method(...$this->getMethodDependency($controller, $method), ...$args);
            }

            return false;

        }

    }