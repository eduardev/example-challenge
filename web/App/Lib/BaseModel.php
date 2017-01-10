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

use Eduardo\Example\App\Lib\Services\Db;
use Eduardo\Example\App\Lib\Services\ElasticSearch;
use Eduardo\Example\App\Services;

abstract class BaseModel
{
    /**
     * @var DiContainer
     */
    private $Di;
    
    /**
     * @var Db
     */
    private $Db;
    
    /**
     * @var ElasticSearch
     */
    protected $Elastic;
    
    /**
     * @var null|string
     */
    protected $table;
    
    /**
     * @var string
     */
    protected $errorMessage = '';
    
    /**
     * BaseModel constructor.
     */
    public function __construct()
    {
        $this->Di       = Services::getDi();
        $this->Db       = $this->Di->db;
        $this->Elastic  = $this->Di->elastic;
        $this->table    = $this->getTableName();
    }
    
    /**
     * @param string $where
     * @param array  $bind
     * @param string $orderBy
     *
     * @return array
     */
    protected function findByWhere($where = '', $bind = [], $orderBy = '')
    {
        return $this->Db->select($this->getTableName(), $where, $bind, $orderBy);
    }
    
    /**
     * @param string $where
     * @param array  $bind
     *
     * @return int
     */
    protected function deleteByWhere($where = '', $bind = [])
    {
        return $this->Db->delete($this->getTableName(), $where, $bind);
    }
    
    /**
     * @param array  $cols
     * @param array  $bind
     * @param string $where
     * @param array  $whereBind
     *
     * @return bool
     */
    protected function updateByWhere($cols = [], $bind = [], $where = '', $whereBind = [])
    {
        return $this->Db->update($this->getTableName(), $cols, $bind, $where, $whereBind);
    }
    
    /**
     * @param array $cols   A numeric index array of the columns
     * @param array $bind   Here is our bind, a numeric index array of values for the provided columns
     *
     * @return int
     */
    protected function insert($cols = [], $bind = [])
    {
        $res = $this->Db->insert($this->getTableName(), $cols, $bind);
        if (!$res) {
            $this->setErrorMessage($this->Db->getError());
        }
        
        return $res;
    }
    
    /**
     * @param $message
     */
    protected function setErrorMessage($message)
    {
        $this->errorMessage = $message;
    }
    
    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
    
    /**
     * @return mixed
     */
    abstract protected function getTableName();
}
