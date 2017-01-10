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

namespace Eduardo\Example\App\Mvc\Controllers;

use Eduardo\Example\App\Lib\BaseController;
use Eduardo\Example\App\Mvc\Models\Book;

class AjaxController extends BaseController
{
    
    public function handleDelete()
    {
        header('Content-Type: application/json');
        
        // Make sure book id is in POST
        if (!isset($_POST['bookId'])) {
            $this->returnError('Book ID not sent');
        }
        
        // Now let's try to delete book
        $Book = new Book();
        if (!$Book->deleteBookById($_POST['bookId'])) {
            $this->returnError($Book->getErrorMessage());
        }
        
        $this->returnSuccess();
    }
    
    /**
     * Return JSON error message
     * @param $msg
     */
    private function returnError($msg)
    {
        echo json_encode([
            'status'    => '0',
            'message'   => $msg
        ]);
        exit;
    }
    
    /**
     * Return JSON success message
     */
    private function returnSuccess()
    {
        echo json_encode([
            'status'    => '1'
        ]);
        exit;
    }
}
