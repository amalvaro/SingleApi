<?php
    include "ApiManager.php";
    include "ApiQueryParser.php";

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
        }

        function executeAndPrintResponse() {
            $qp = $this->apiQueryParser;
            $this->printResponse(
                $this->apiManager->findRouteAndExecute(
                    $qp->getControllerName(),
                    $qp->getMethodName(),
                    $qp->getArgs()
                )
            );
        }

        private function printResponse($controllerResponse) {
            print json_encode($controllerResponse);
        }

    }
