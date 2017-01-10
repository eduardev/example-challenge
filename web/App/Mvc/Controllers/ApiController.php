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

class ApiController extends BaseController
{
    public function handleInsert()
    {
        // Set header for JSON reply
        header('Content-Type: application/json');
        // Make sure all fields are sent through
        if (!isset($_POST['bookcase_id']) ||
            !isset($_POST['shelf_id']) ||
            !isset($_POST['isbn']) ||
            !isset($_POST['title']) ||
            !isset($_POST['author']) ||
            !isset($_POST['year'])
        ) {
            $this->returnError('Some or all the required fields were not given');
        }
        // Try to insert
        $Book = new Book();
        $bookId = $Book->insertBook(
            $_POST['bookcase_id'],
            $_POST['shelf_id'],
            $_POST['isbn'],
            $_POST['title'],
            $_POST['author'],
            $_POST['year']
        );
        // Validate insertion
        if (!$bookId) {
            $this->returnError($Book->getErrorMessage());
        }
        // Return success message
        $this->returnSuccess([
            'id' => $bookId
        ]);
    }
    
    /**
     * Return JSON error message
     * @param $msg
     */
    private function returnError($msg)
    {
        // Set error code to 400
        http_response_code(400);
        // Return message
        echo json_encode([
            'message'   => $msg
        ]);
        exit;
    }
    
    /**
     * Return JSON success message
     * @param array $payload
     */
    private function returnSuccess(array $payload)
    {
        echo json_encode(
            array_merge([
                'status'    => 'ok'
            ], $payload)
        );
        exit;
    }
}
