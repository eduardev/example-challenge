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

use Eduardo\Example\App\Lib\DiContainer;
use Eduardo\Example\App\Lib\Services\Router;

/**
 * Class Routes
 * @package Eduardo\Example\App
 */
class Routes
{

    /**
     * @param DiContainer $Di
     * @return Router
     */
    public static function buildRouter(DiContainer $Di)
    {
        // Create Router
        $Router = new Router($Di);

        // Add all our routes (URI, Controller name, Action name, [disable view?])
        $Router->addRoute('/', 'main', 'index');
        $Router->addRoute('/ajax/book/delete', 'ajax', 'delete', true);
        $Router->addRoute('/api/books', 'api', 'insert', true);

        // Return Router object
        return $Router;
    }
}
