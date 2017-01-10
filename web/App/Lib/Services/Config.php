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

/**
 * Class Config
 * @package Eduardo\Example\App\Lib\Services
 */
class Config
{

    /**
     * @var array   This will hold all our configurations array
     */
    public $configurations = [];

    /**
     * @var string  This is our configuration files location
     */
    private $confFile;
    
    /**
     * Config constructor.
     *
     * @param array $paths
     *
     * @throws \Exception
     */
    public function __construct(array $paths)
    {
        // Make sure application absolute path is provided and valid
        if (!isset($paths['absPath']) || !is_dir($paths['absPath'])) {
            throw new \Exception('Absolute path not provided or directory not found');
        }

        // Make sure application absolute path is provided and valid
        if (!isset($paths['confPath'])) {
            throw new \Exception('Configurations path not provided');
        }

        // Set object variable for our absolute path to be available application wide
        $this->configurations['paths']['absolute'] = $paths['absPath'];
        // Check our configuration files
        $this->checkConfigurations($paths);
        // Read and set all our configurations
        $this->readConfigurations();
    }

    /**
     * Checks for paths and file locations and sets the final
     * path for the configuration file
     *
     * @param array $paths
     *
     * @throws \Exception
     */
    private function checkConfigurations(array $paths)
    {
        // Set default environment for development
        $envDir = '/dev';

        // Check if environment is production
        if (isset($_ENV['ExampleProduction'])) {
            $envDir = '/prod';
        }

        // Concatenate full path location
        $confDir    = $this->configurations['paths']['absolute'] . $paths['confPath'] . $envDir;

        // Make sure provided string is a directory
        if (!is_dir($confDir)) {
            throw new \Exception('Configurations directory not found: ' . $confDir);
        }

        // Concatenate full path location
        $confFile   = $confDir . '/config.json';

        // Make sure configuration file exists
        if (!file_exists($confFile)) {
            throw new \Exception('Configurations file not found: ' . $confFile);
        }

        $this->confFile = $confFile;
    }
    
    /**
     * This method will read the configuration file
     * and will set it into our object property
     *
     * @throws \Exception
     */
    private function readConfigurations()
    {
        // Get config file contents and check if success
        if (!$confJson = file_get_contents($this->confFile)) {
            throw new \Exception('Configurations file could not be read');
        }
        // Convert it to an assoc array and check for success
        if (!is_array($confArr = json_decode($confJson, true))) {
            throw new \Exception('Configurations data could not be converted to array');
        }
        // Convert json data into array, merge with current settings and set as attribute
        $this->configurations = array_replace_recursive(
            $this->configurations,
            $confArr
        );
    }
}
