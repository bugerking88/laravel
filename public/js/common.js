$(document).ready( function() {
    $('#dataTable').DataTable();
    $(document).on('click', "#edit-item", function() {
        $(this).addClass('edit-item-trigger-clicked'); //useful for identifying which trigger was clicked and consequently grab data from the correct row and not the wrong one.

        var options = {
            'backdrop': 'static'
        };
        $('#edit-modal').modal(options);
    });
     // on modal hide
     $('#edit-modal').on('hide.bs.modal', function() {
        $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked');
        $("#edit-form").trigger("reset");
    })
});
$('.delete').click(function (e) {

    var message = "Are you sure to delete?";
    if(window.location.pathname == '/customers') {
        message = "If you delete the customer, all licenses under related customer will be deleted too. <br/> Are you sure to delete?";
    }
    $('.confirmation-message').html(message);
    e.preventDefault();
    $("#confirmModal").modal({
        'backdrop' : 'static'
    })
    $('#yes').click(function (ele) {
        $(e.target).closest('form').submit();
    });
    
    // if(confirm('Are you sure to delete')) {
    //     $(e.target).closest('form').submit();
    // }
});