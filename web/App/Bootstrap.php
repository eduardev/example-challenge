<?php

/**
 * The MIT License
 *
 * Copyright 2017 Eduardo Pereira <email@eduardopereira.pt>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Eduardo\Example\App;

use Eduardo\Example\App\Lib\BaseController;
use Eduardo\Example\App\Lib\DiContainer;

/**
 * Class Bootstrap
 * @package Eduardo\Example\App
 */
class Bootstrap
{

    /**
     * @var DiContainer
     */
    private $Di;

    /**
     * Bootstrap constructor.
     *
     * @param DiContainer $Di
     */
    public function __construct(DiContainer $Di)
    {
        $this->Di       = $Di;
    }

    public function run()
    {
        // Check for URI passed by NGINX
        if (!isset($_SERVER['REQUEST_URI'])) {
            throw new \Exception('Server Request URI not found');
        }
        // Check for namespace configurations
        if (!isset($this->Di->Config->configurations['namespaces']['controllers'])) {
            throw new \Exception('Controllers namespaces not defined in configurations');
        }
        // Set some local variables
        $uriParts               = parse_url($_SERVER['REQUEST_URI']);
        $uri                    = $uriParts['path'];
        $route                  = $this->Di->router->getRoute($uri);
        $libraryNamespace       = $this->Di->Config->configurations['namespaces']['library'];
        $controllersNamespace   = $this->Di->Config->configurations['namespaces']['controllers'];
        $controllerClassName    = ucwords($route['controller']) . 'Controller';
        $controllerClass        = $controllersNamespace . '\\' . $controllerClassName;
        // Check if controller class exists
        if (!class_exists($controllerClass)) {
            throw new \Exception('Controller not found: ' . $controllerClassName);
        }
        // Instantiate our target controller class
        /* @var BaseController $Controller */
        $Controller = new $controllerClass();
        // Make sure our controller extends base controller
        if (!is_a($Controller, $libraryNamespace . '\\BaseController')) {
            throw new \Exception('Controller ('.$controllerClassName.') does not extends BaseController.');
        }
        // Inject DI into controller
        $Controller->setDi($this->Di);
        // Finally execute magic method to start controller
        $magicMethodName = 'execute____' . ucwords($route['action']);
        $Controller->$magicMethodName();
    }
}
