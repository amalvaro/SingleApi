<?php
    include "ApiManager.php";
    include "ApiQueryParser.php";


    class SessionKeys {
        public const SESSION_AUTH_KEY = "AUTH";
    }

    class AuthState {
        public const AUTHORIZED = true;
        public const GUEST = false;
    }

    class ApiSession {

        /** @var array */
        private $request;

        /** @var ApiManager */
        private $apiManager;

        /** @var ApiQueryParser */
        private $apiQueryParser;

        function __construct($request, $routes) {
            $this->request = $request;
            $this->apiManager = new ApiManager($routes);
            $this->apiQueryParser = new ApiQueryParser($request);

            if(!session_id()) {
                if($this->apiQueryParser->getToken() != false)
                    session_id($this->apiQueryParser->getToken());

                session_start();
            }

        }

        function executeAndPrintResponse() {
            $qp = $this->apiQueryParser;

            $controllerName = $qp->getControllerName();
            $methodName = $qp->getMethodName();
            $methodArguments = $qp->getArgs();

            $methodExternalAccess = $this->apiManager->canExternal($controllerName, $methodName);

            if((!$methodExternalAccess  && $this->getSessionValue(SessionKeys::SESSION_AUTH_KEY)) == AuthState::AUTHORIZED
                || $methodExternalAccess) {
                $this->printResponse(
                    $this->apiManager->findRouteAndExecute(
                        $controllerName, $methodName, $methodArguments
                    )
                );
            }

        }

        public function setSessionValue($key, $value) {
            $_SESSION[$key] = $value;
        }

        public function getSessionValue($key, $default = false) {
            return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
        }

        private function printResponse($controllerResponse) {
            print json_encode($controllerResponse);
        }

    }
