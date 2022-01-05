$('#login-form').on('submit', function(event)
{

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    event.preventDefault();

    Swal.fire({
        text : "Mohon menunggu..."
    });

    Swal.showLoading();

    $.ajax({
        url: $(this).attr("action"),
        method:"POST",
        data:new FormData(this),
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: (data) => {

            if (data.status == "ok" && data.response == "login-user") {
                Swal.close();
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: data.message,
                });
                setTimeout(function(){
                    window.location.href = data.route;
                }, 1500);
            }
        },
        error: (data) => {
            if (data.responseJSON.status == "fail-validator") {
                Swal.fire({
                    title: 'Perhatian!',
                    text: data.responseJSON.message,
                    icon: 'error',
                    confirmButtonText: 'Oke'
                })
            }

            if (data.responseJSON.status == "fail-email") {
                Swal.fire({
                    title: 'Perhatian!',
                    text: data.responseJSON.message,
                    icon: 'error',
                    confirmButtonText: 'Oke'
                })
            }

            if (data.responseJSON.status == "fail-password") {
                Swal.fire({
                    title: 'Perhatian!',
                    text: data.responseJSON.message,
                    icon: 'error',
                    confirmButtonText: 'Oke'
                })
            }

            if (data.responseJSON.status == "fail-verification") {
                Swal.fire({
                    title: 'Perhatian!',
                    text: data.responseJSON.message,
                    icon: 'error',
                    confirmButtonText: 'Oke'
                });

                $('#btn-verification-email').show();
            }
        }
    });
});
