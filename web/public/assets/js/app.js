$(document).ready(function() {

    /**
     * On click event for table row delete button
     * We will populate modal with data to confirm book delete
     */
    $("table button.remove").click(function(e){
        // Get values from button
        var $this       = $(this);
        var bookId      = $this.data("book-id");
        var bookTitle   = $this.data("book-title");
        // Get modal DOM elements to use
        var $modalTitle = $('#modal-del-book-name');
        var $modalBtn   = $('#modal-del-btn');
        // Set values on modal DOM
        $modalTitle.html(bookTitle);
        $modalBtn.attr("data-book-id", bookId);
    });

    /**
     * On click event for delete modal confirm button
     * It will trigger an ajax request to remove item
     */
    $("#modal-del-btn").click(function(e){
        // Get values from button
        var $this       = $(this);
        var bookId      = $this.data("book-id");
        // Get modal DOM elements to use
        var $modalTitle = $('#modal-del-book-name');
        // Disable button
        $this.prop("disabled", true);
        console.log(bookId);
        // Call AJAX endpoint (/ajax/book/delete)
        $.ajax({
            data: {'bookId':bookId},
            type:'POST',
            url: '/ajax/book/delete',
            cache: false,
            success: function(response) {
                if (response.status == 1) {
                    console.log('yesss');
                    location.reload();
                } else {
                    alert(response.message);
                    $('#removeModal').modal('hide');
                    $this.prop("disabled", false);
                }
            }
        });
    });

    /**
     * reset values of modal on close
     */
    $('#removeModal').on('hidden.bs.modal', function (e) {
        // Get modal DOM elements to use
        var $modalTitle = $('#modal-del-book-name');
        var $modalBtn   = $('#modal-del-delete-btn');
        // Set values on modal DOM
        $modalTitle.html('??');
        $modalBtn.attr("data-book-id", 0);
    })

});
