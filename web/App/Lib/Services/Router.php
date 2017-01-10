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

namespace Eduardo\Example\App\Lib\Services;

use Eduardo\Example\App\Bootstrap;
use Eduardo\Example\App\Lib\DiContainer;

/**
 * Class Router
 * @package Eduardo\Example\App\Lib
 */
class Router
{
    /**
     * @var DiContainer
     */
    private $Di;
    /**
     * @var array
     */
    private $routes;
    
    /**
     * @var null|string
     */
    public $currController;
    
    /**
     * @var null|string
     */
    public $currAction;
    
    /**
     * @var null|string
     */
    public $useView;

    /**
     * Router constructor.
     *
     * @param DiContainer $Di
     */
    public function __construct(DiContainer $Di)
    {
        $this->Di               = $Di;
        $this->routes           = [];
        $this->currAction       = null;
        $this->currController   = null;
    }
    
    /**
     * @param      $uri
     * @param      $controller
     * @param      $action
     * @param bool $disableView
     *
     * @throws \Exception
     */
    public function addRoute($uri, $controller, $action, $disableView = false)
    {
        if (!isset($uri) || !isset($uri) || !isset($uri)) {
            throw new \Exception('You must provide URL, Controller name and Action name');
        }
        $this->routes[$uri] = [
            'controller'    => $controller,
            'action'        => $action,
            'useView'       => !($disableView)
        ];
    }

    /**
     * This method will be called, at least, once inside our
     * Application bootstrap Run(), and it will be
     * responsible for determining which controller
     * and action should the be routed to, returning
     * the values and storing them locally for
     * easier access on other instances.
     *
     * @param $uri
     *
     * @return array|mixed
     * @see Bootstrap::run()
     */
    public function getRoute($uri)
    {
        if (isset($this->routes[$uri])) {
            // Before returning, set local values
            $this->currController   = $this->routes[$uri]['controller'];
            $this->currAction       = $this->routes[$uri]['action'];
            $this->useView          = (bool)$this->routes[$uri]['useView'];
            return $this->routes[$uri];
        } else {
            // Before returning, set local values
            $this->currController   = 'main';
            $this->currAction       = '404';
            return [
                'controller'    => 'main',
                'action'        => '404',
                'useView'       => true
            ];
        }
    }
}
