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
use Eduardo\Example\App\Lib\Services\Config;
use Eduardo\Example\App\Lib\Services\Db;
use Eduardo\Example\App\Lib\Services\ElasticSearch;
use Eduardo\Example\App\Lib\Services\View;

/**
 * Class Routes
 * @package Eduardo\Example\App
 */
class Services
{
    
    public static $Di = null;
    
    /**
     * @param Config $Config
     *
     * @return DiContainer
     */
    public static function buildDI(Config $Config)
    {
        // Create our DI Container Object
        $Di = new DiContainer($Config);

        // Add all our services to DI
        $Di->setService('router', Routes::buildRouter($Di));
        $Di->setService('db', new Db($Di->Config));
        $Di->setService('elastic', new ElasticSearch($Di->Config));
        $Di->setService('view', new View($Di->Config));
        // Set static access to DI
        self::$Di = $Di;
        // Return our DI container object
        return self::$Di;
    }
    
    /**
     * @return DiContainer
     * @throws \Exception
     */
    public static function getDi()
    {
        if (self::$Di === null) {
            throw new \Exception('Dependency Injection not created yet.');
        }
        return self::$Di;
    }
}
