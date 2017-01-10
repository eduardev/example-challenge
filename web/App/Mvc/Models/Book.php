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

namespace Eduardo\Example\App\Mvc\Models;

use Eduardo\Example\App\Lib\BaseModel;
use Biblys\Isbn\Isbn;

class Book extends BaseModel
{
    /**
     * @return string
     */
    protected function getTableName()
    {
        return 'books';
    }
    
    /**
     * @return array
     */
    public function getAllBooks()
    {
        return $this->findByWhere('1 = ?', [1]);
    }
    
    /**
     * @param $id
     *
     * @return array|bool       Either the table row, or false if not found
     */
    public function getBookById($id)
    {
        if ($rows = $this->findByWhere('id = ?', [$id])) {
            return $rows[0];
        }
        return false;
    }
    
    /**
     * @param array $ids
     *
     * @return array
     */
    public function getBooksByIds(array $ids)
    {
        // Convert ids array to a comma separated string
        $prepare    = '';
        $values     = [];
        foreach ($ids as $id) {
            $prepare    .= '?,';
            $values[]    = filter_var($id, FILTER_VALIDATE_INT);
        }
        $prepare    = rtrim($prepare, ',');
        // Set order by FIELD to keep order from Elastic
        $orderBy    = 'FIELD(id, ' . $prepare . ')';
        // merge the exact same array, will keep duplicate values, in order ;)
        $finalVals  = array_merge($values, $values);
        return $this->findByWhere("id IN ($prepare)", $finalVals, $orderBy);
    }
    
    /**
     * @param $id
     *
     * @return bool
     */
    public function deleteBookById($id)
    {
        // Validate book id
        if (!$this->validateInt($id)) {
            $this->setErrorMessage('Book ID not valid');
            return false;
        }
        
        // First delete from DB
        if ($this->deleteByWhere('id = ?', [$id])) {
            // Now delete from elastic
            $this->Elastic->delete($id);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * @param int   $id
     * @param array $cols
     * @param array $bind
     *
     * @return bool
     */
    public function updateBookById($id, $cols = [], $bind = [])
    {
        // Check if we're updating any of text search values, so we update elastic
        $targets    = ['title', 'author'];
        if (count(array_intersect($cols, $targets)) == count($targets)) {
            // TODO: Update elastic
        }
        return $this->updateByWhere($cols, $bind, 'id = ?', [$id]);
    }
    
    /**
     * @param $bookcaseId
     * @param $shelfId
     * @param $isbn
     * @param $title
     * @param $author
     * @param $year
     *
     * @return int              The inserted row id, or 0 if failed
     */
    public function insertBook($bookcaseId, $shelfId, $isbn, $title, $author, $year)
    {
        // Validate fields
        if (!$this->validateInt($bookcaseId)) {
            $this->setErrorMessage('Bookcase ID not valid');
            return false;
        }
        if (!$this->validateInt($shelfId)) {
            $this->setErrorMessage('Shelf ID not valid');
            return false;
        }
        if (!$this->validateISBN($isbn)) {
            $this->setErrorMessage('ISBN not valid');
            return false;
        }
        if (!$this->validateTitleOrAuthor($title)) {
            $this->setErrorMessage('Title not valid: must be between 3 and 256 characters');
            return false;
        }
        if (!$this->validateTitleOrAuthor($author)) {
            $this->setErrorMessage('Author name not valid: must be between 3 and 256 characters');
            return false;
        }
        if (!$this->validateYear($year)) {
            $this->setErrorMessage('Year not valid');
            return false;
        }
        // Great, let's try to insert book into DB
        $bookId = $this->insert(
            ['bookcase_id', 'shelf_id', 'isbn', 'title', 'author', 'year'],
            [$bookcaseId, $shelfId, $isbn, $title, $author, $year]
        );
        // Did we got a book ID from SQL?
        if ($bookId) {
            // great, finally insert document into elastic, passing the mysql id and BODY payload array
            $res2 = $this->Elastic->insert($bookId, [
                'title'     => $title,
                'author'    => $author,
            ]);
        }
        
        return $bookId;
    }
    
    /**
     * @param $query
     *
     * @return array
     */
    public function searchBook($query)
    {
        // Sanitize search query
        $queryString = filter_var($query, FILTER_SANITIZE_STRING);
        // Search elastic
        $response = $this->Elastic->search($queryString);
        // Documents found?
        if ($response['hits']['total'] > 0) {
            $docs = $response['hits']['hits'];
            // Get IDs
            $ids = [];
            foreach ($docs as $doc) {
                $ids[] = $doc['_id'];
            }
            // Return the result from SQL
            return $this->getBooksByIds($ids);
        }
        
        return [];
    }
    
    /**
     * @param $int
     *
     * @return bool
     */
    private function validateInt($int)
    {
        // Sanitize value first, and then validate
        $bookId = filter_var($int, FILTER_SANITIZE_NUMBER_INT);
        if (!filter_var($bookId, FILTER_VALIDATE_INT)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @param $value
     *
     * @return bool
     */
    private function validateTitleOrAuthor($value)
    {
        // Sanitize value first, and then validate
        $bookId = filter_var($value, FILTER_SANITIZE_STRING);
        if (strlen($bookId) > 256 || strlen($bookId) < 3) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @param $isbn
     *
     * @return bool
     */
    private function validateISBN($isbn)
    {
        // Create ISBN object
        $ISBN = new Isbn($isbn);
        if (!$ISBN->isValid()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @param $year
     *
     * @return bool
     */
    private function validateYear($year)
    {
        // Sanitize value first, and then validate
        $year = filter_var($year, FILTER_SANITIZE_NUMBER_INT);
        if (!filter_var($year, FILTER_VALIDATE_INT)) {
            return false;
        }
        // Yeah year 868 because it might as well be the "Gutenberg Bible" =)
        if ($year < 868 || $year > date('Y')) {
            return false;
        }
        
        return true;
    }
}
