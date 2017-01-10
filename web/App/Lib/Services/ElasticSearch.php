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

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticSearch
{
    private $config;
    
    /**
     * @var Client
     */
    private $conn;
    
    /**
     * ElasticSearch constructor.
     *
     * @param Config $Config
     */
    public function __construct(Config $Config)
    {
        $this->config = $Config->configurations['elastic'];
    }
    
    /**
     * @param string $id
     * @param array  $body
     *
     * @return array
     */
    public function insert(string $id, array $body)
    {
        // Set connection
        $this->setConnection();
        // Try to insert document
        $response = $this->conn->index([
            'index' => 'example',
            'type'  => 'books',
            'id'    => $id,
            'body'  => $body
        ]);
        
        return $response;
    }
    
    /**
     * @param string $elasticId
     *
     * @return array
     */
    public function delete(string $elasticId)
    {
        // Set connection
        $this->setConnection();
        // Create query
        $response = $this->conn->delete([
            'index' => 'example',
            'type'  => 'books',
            'id'    => $elasticId
        ]);
        
        return $response;
    }
    
    /**
     * This is our main search method.
     *
     * Here we should tweak the search according to our needs,
     * maybe using some different analyzers or query types.
     *
     * I decided to go for a simple multi field match, using best field strategy
     * and implementing a tie breaker to make it so the fields other than
     * the "best" one also add to the score on smaller importance
     *
     * @param string $query The search term to look for
     *
     * @return array
     */
    public function search(string $query)
    {
        // Set connection
        $this->setConnection();
        // Create query
        $response = $this->conn->search([
            'index' => 'example',
            'type'  => 'books',
            'body'  => [
                'query'     => [
                    'multi_match'   => [
                        'query'         => $query,
                        'type'          => 'best_fields',
                        'fields'        => [
                            'title',
                            'author'
                        ],
                        "tie_breaker"   => 0.3
                    ]
                ]
            ]
        ]);
        
        return $response;
    }
    
    /**
     * Opens connection to DB via PDO
     */
    private function setConnection()
    {
        if ($this->conn) {
            return;
        }
        $this->conn = ClientBuilder::create()
            ->setHosts([$this->config['host']])
            ->setRetries(2)
            ->build();
    }
}
