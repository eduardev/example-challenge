
<div class="container" id="main-page">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>
                    <a href="/">
                        Example <small>Personal Library Manager</small>
                    </a>
                </h1>
            </div>
        </div>
        <div class="col-md-12">
            <?php if (!count($this->books)) : ?>
                <div class="alert alert-warning" role="alert">
                    <strong>Oh snap! No books yet.</strong>
                    <div>User our fabulous API to add some now with <code>POST /api/books</code> =)</div>
                </div>
            <?php else : ?>
                <div>
                    <form class="form-inline" id="search-form">
                        <div class="form-group">
                            <input type="text" id="q" name="q" value="<?= $this->q; ?>" placeholder="Search book" class="form-control input-sm">
                        </div>
                        
                        <button type="submit" class="btn btn-xs btn-success">Search</button>
                    </form>
                    <table class="table table-hover table-condensed table-bordered">
                        <thead>
                            <tr>
                                <td>Id</td>
                                <td>Bookcase</td>
                                <td>Shelf</td>
                                <td>Title</td>
                                <td>Author</td>
                                <td>Year</td>
                                <td>ISBN</td>
                                <td>Tools</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($this->books as $book) : ?>
                            <tr>
                                <td><?= $book['id']; ?></td>
                                <td><?= $book['bookcase_id']; ?></td>
                                <td><?= $book['shelf_id']; ?></td>
                                <td><?= $book['title']; ?></td>
                                <td><?= $book['author']; ?></td>
                                <td><?= $book['year']; ?></td>
                                <td><?= $book['isbn']; ?></td>
                                <td>
                                    <button class="remove btn btn-xs btn-danger" data-book-id="<?= $book['id']; ?>" data-book-title="<?= $book['title']; ?>" data-toggle="modal" data-target="#removeModal">delete</button>
                                    <button class="edit btn btn-xs btn-info" data-toggle="modal" data-target="#editModal">edit</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<footer class="container">
    <div class="row">
        <div class="col-md-6 credits">
            Developed by <a href="https://eduardopereira.pt" target="_blank">Eduardo Pereira</a> | <?= date('Y'); ?>
        </div>
    </div>
</footer>
<!-- Modal Delete -->
<div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="deleteModalLabel">Delete "<span id="modal-del-book-name">???</span>"</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this book?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="modal-del-btn" data-book-id="">Yes, delete</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editModalLabel">Oppps!</h4>
            </div>
            <div class="modal-body">
                We're sorry but, apparently, editing books fell out of scope of the provided example.<br>
                If you really insist this dummy book is wrong, maybe try to delete it and use our beautiful API to insert a new one ;)
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

