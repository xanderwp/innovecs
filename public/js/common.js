$( document ).ready(function() {
    $("#add-transactions-form .btn-submit").click(function(e){
        e.preventDefault();
        $('.alert-danger').hide();
        $('.alert-danger').html('');

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url:  $("#add-transactions-form").attr('action'),
            data: $("#add-transactions-form").serialize(),
            success: function(data){
                $("#result").html(data.success);
                $("#result").show();
                $('#add-transactions-form')[0].reset();
                $('#result').delay(5000).hide(0);
            },
            error: function (request, status, error) {
                json = $.parseJSON(request.responseText);
                $.each(json.errors, function(key, value){
                    $('.alert-danger').show();
                    $('.alert-danger').append('<p>'+value+'</p>');
                });
                $("#result").html('');
                $("#result").hide();
            }
        });
    });
});
