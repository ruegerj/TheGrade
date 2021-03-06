<?
    require_once($GLOBALS["config"]["paths"]["resources"]["interface"] . "/IRequest.php");
    require_once($GLOBALS["config"]["paths"]["resources"]["module"] . "/TemplateHelper.php");

    class Router {
        private $request;
        private $supportedHttpMethods = array(
            "GET", 
            "POST"
        );

        //constructor stores request in property
        function __construct(IRequest $request) 
        {
            $this->request = $request;
        }

        //gets triggered if a undefined method gets called
        //with this behavour the router can be used with dynamic handlers (methods and callbacks)
        function __call(string $name, array $args)
        {
            list($route, $method) = $args;

            if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
                $this->invalidMethodHandler();
            }

            $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
        }

        /**
        * Removes trailing forward slashes from the right of the route.
        * @param route (string)
        */
        private function formatRoute(string $route) : string
        {
            $result = rtrim($route, "/");
            if ($result === "") {
                return "/";
            }

            return $result;
        }

        private function invalidMethodHandler() : void
        {
            TemplateHelper::renderErrorPage("405", "Method is not allowed", "Requested HTTp-MEthod is not allowed");
            die();
        }

        private function defaultRequestHandler() : void
        {
            TemplateHelper::renderErrorPage("404", "Requested page doesn't exist", "File not found");          
            die();  
        }

        /**
        * Resolves a route, calls the given callback
        */
        function resolve() : void
        {
            $methodDictionary = $this->{strtolower($this->request->requestMethod)};
            $formatedRoute = $this->formatRoute($this->request->requestUri);            
            if (key_exists($formatedRoute, $methodDictionary)) {
                $method = $methodDictionary[$formatedRoute];
                if (is_null($method)) {
                    $this->defaultRequestHandler();
                    return;
                }                
                echo call_user_func_array($method, array($this->request));
               
            }            
            else
            {
                $this->defaultRequestHandler();
            }            
        }

        function __destruct()
        {
            $this->resolve();
        }
    }
?>