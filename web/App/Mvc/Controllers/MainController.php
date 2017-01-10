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

class MainController extends BaseController
{
    
    public function handleIndex()
    {
        // Check if query params for search
        $this->view->q = '';
        if (isset($_GET['q'])) {
            // Set value for view
            $this->view->q  = $_GET['q'];
            // Search books
            $books = (new Book())->searchBook($this->view->q);
        } else {
            // No query, get all books
            $books  = (new Book())->getAllBooks();
        }
        // Set book for view
        $this->view->books = $books;
    }
    
    public function handle404()
    {
        echo '404';
    }
}
