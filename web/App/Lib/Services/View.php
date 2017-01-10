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

use Eduardo\Example\App\Lib\BaseController;

class View
{
    /**
     * @var null|string
     */
    private $template;
    
    /**
     * @var string
     */
    private $viewsPath;
    
    /**
     * @var bool
     */
    private $rendered = false;
    
    /**
     * View constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->template     = null;
        $this->viewsPath    = $config->configurations['paths']['absolute'] . $config->configurations['paths']['views'];
    }
    
    /**
     * Looks for, and sets, our view template file,
     * according to current Router controller
     * and action. This is set on the
     * base controller just before calling
     * the handler action method.
     *
     * @param $controller
     * @param $action
     *
     * @throws \Exception
     * @see BaseController::setView()
     */
    public function setView($controller, $action)
    {
        // Let's make sure view template exists
        $this->template = $this->viewsPath . '/' . strtolower($controller) . '/' . strtolower($action) . '.php';
        if (!file_exists($this->template)) {
            throw new \Exception('View file (' . $this->template . ') not found in: ' . $this->viewsPath);
        }
    }
    
    /**
     * Method responsible to render our view template.
     * it's automatically called by base controller.
     */
    public function render()
    {
        if (!$this->rendered) {
            $this->rendered = true;
            ob_start();
            include($this->template);
            $content = ob_get_contents();
            ob_end_clean();
            $this->viewOpen();
            echo $content;
            $this->viewClose();
        }
    }
    
    /**
     * Output opening html
     */
    private function viewOpen()
    {
        ?>
        <!doctype html>
        <html class="no-js" lang="">
            <head>
                <meta charset="utf-8">
                <meta http-equiv="x-ua-compatible" content="ie=edge">
                <title></title>
                <meta name="description" content="">
                <meta name="viewport" content="width=device-width, initial-scale=1">
    
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
                <link rel="stylesheet"  href="/assets/css/app.css">
                <script
                    src="https://code.jquery.com/jquery-3.1.1.min.js"
                    integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
                    crossorigin="anonymous"></script>
            </head>
            <body>
        <?php
    }
    
    /**
     * Output closing html
     */
    private function viewClose()
    {
        ?>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
                <script src="/assets/js/app.js"></script>
            </body>
        </html>
        <?php
    }
}
