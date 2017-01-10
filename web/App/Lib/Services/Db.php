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
 * Class Db
 * @package Eduardo\Example\App\Lib\Services
 */
class Db
{

    /**
     * @var null|\PDO
     */
    private $conn = null;
    
    /**
     * @var array
     */
    private $configs;
    
    /**
     * @var
     */
    private $error;
    
    /**
     * Db constructor.
     *
     * @param Config $Config
     */
    public function __construct(Config $Config)
    {
        $this->configs = $Config->configurations['mysql'];
    }
    
    /**
     * @param string $table
     * @param string $where
     * @param array  $bind
     * @param string $orderBy
     *
     * @return array
     */
    public function select($table = '', $where = '', $bind = [], $orderBy = '')
    {
        $this->setConnection();

        $sql        = "SELECT * FROM $table WHERE $where";
        if ($orderBy) {
            $sql .= ' ORDER BY ' . $orderBy;
        }
        $statement  = $this->conn->prepare($sql);
        $statement->execute($bind);
        $rows       = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $rows;
    }

    /**
     * @param string $table     The name of the table
     * @param array  $cols      A numeric array with column names
     * @param array  $bind      A numeric array with the bind values for columns
     *
     * @return int
     */
    public function insert($table, $cols = [], $bind = [])
    {
        $this->setConnection();
        $columns    = '';
        $values     = '';
        foreach ($cols as $col) {
            $columns .= "$col,";
            $values  .= '?,';
        }
        $columns    = rtrim($columns, ',');
        $values     = rtrim($values, ',');
        $sql        = "INSERT INTO $table ($columns) VALUES ($values)";
        $statement  = $this->conn->prepare($sql);
        
        if ($statement->execute($bind)) {
            return (int) $this->conn->lastInsertId();
        }
        
        $this->error = implode(":", $statement->errorInfo());
        return 0;
    }
    
    /**
     * @param string $table
     * @param string $where
     * @param array  $bind
     *
     * @return int
     */
    public function delete($table = '', $where = '', $bind = [])
    {
        $this->setConnection();

        $sql        = "DELETE FROM $table WHERE $where";
        $statement  = $this->conn->prepare($sql);
        if ($statement->execute($bind)) {
            return $statement->rowCount();
        }
    
        $this->error = implode(":", $statement->errorInfo());
        return 0;
    }
    
    /**
     * @param string $table
     * @param array  $cols
     * @param array  $bind
     * @param string $where
     * @param array  $whereBind
     *
     * @return bool
     */
    public function update($table = '', $cols = [], $bind = [], $where = '', $whereBind = [])
    {
        $this->setConnection();
        $set        = '';
        foreach ($cols as $col) {
            $set .= "$col = ?,";
        }
        $set        = rtrim($set, ',');
        $sql        = "UPDATE $table SET $set WHERE $where";
        $fullBind   = array_merge($bind, $whereBind);
        $statement  = $this->conn->prepare($sql);
        if ($statement->execute($fullBind)) {
            return true;
        }
    
        $this->error = implode(":", $statement->errorInfo());
        return false;
    }
    
    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Opens connection to DB via PDO
     */
    private function setConnection()
    {
        if ($this->conn) {
            return;
        }
        $this->conn = new \PDO(
            'mysql:host=' . $this->configs['host'] . ';dbname=example',
            $this->configs['user'],
            $this->configs['pass']
        );
        // Set error exception output
        //$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
