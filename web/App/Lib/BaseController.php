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

namespace Eduardo\Example\App\Lib;

use Eduardo\Example\App\Lib\Services\View;

class BaseController
{
    /**
     * @var DiContainer
     */
    protected $Di;
    
    /**
     * @var View
     */
    protected $view;
    
    /**
     * @param DiContainer $Di
     */
    public function setDi(DiContainer $Di)
    {
        $this->Di = $Di;
    }
    
    /**
     * We'll use the magic __call to build our View, call our
     * controller handler and finally render the view.
     *
     * @param $name
     * @param $arguments
     *
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        // Try to break called method name
        $methodNameParts    = explode('____', $name);
        // Make sure it's our application execution command
        if (count($methodNameParts) !== 2 || $methodNameParts[0] != 'execute') {
            trigger_error('Call to undefined method '.__CLASS__.'::'.$name.'()', E_USER_ERROR);
        }
        // Now make sure intended HANDLE method is defined in child controller
        $methodName = 'handle' . $methodNameParts[1];
        if (!method_exists($this, $methodName)) {
            die('<h2>Method not found: ' . $methodName . '</h2>');
        }
        // We need to set our view before we call handle method
        if ($this->Di->router->useView) {
            $this->setView($methodNameParts[1]);
        }
        // Great, call the handler method
        $this->$methodName();
        // And finally render our view
        if ($this->Di->router->useView) {
            $this->renderView();
        }
    }
    
    /**
     * @param $action
     */
    private function setView($action)
    {
        // Inject View service directly inside controller for easier access and set template in view
        $this->view = $this->Di->view;
        $this->view->setView($this->Di->router->currController, $action);
    }
    
    /**
     * Were we issue View service render action, will output to screen
     */
    private function renderView()
    {
        $this->view->render();
    }
}
