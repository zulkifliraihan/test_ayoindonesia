@extends('dashboard.dashboard')
@section('custom_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css" integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @media (min-width: 992px){
            .datatable-user-view{
                padding: 0 10px
            }
        }
    </style>
@endsection
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Data Players</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Data Players
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                    <div class="form-group breadcrumb-right">
                        <div class="dropdown">
                            <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="modal" data-target="#modalCreate">
                                <i data-feather="plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Ajax Sourced Server-side -->
                <section id="ajax-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom">
                                </div>
                                <div class="card-datatable datatable-user-view">
                                    <table id="table-players" class="datatables-ajax table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pemain</th>
                                                <th>Nomor Punggung</th>
                                                <th>Tinggi Badan</th>
                                                <th>Berat Badan</th>
                                                <th>Posisi</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!--/ Ajax Sourced Server-side -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

    @include('dashboard.components.player.form')

@endsection
@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var dropify = $('.dropify').dropify();
        var table;

        $(function() {
            table = $('#table-players').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                        url: "{{ route('admin.players.index') }}",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className : "text-center width-100"},
                    {data: 'nama', name: 'nama'},
                    {data: 'nomor_punggung', name: 'nomor_punggung'},
                    {data: 'tinggi', name: 'tinggi'},
                    {data: 'berat', name: 'berat'},
                    {data: 'posisi', name: 'posisi'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, className : "text-center"},
                ]
            });
            table.on( 'order.dt search.dt', function () {
                table.column(0, {order:'applied', search:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                });
            }).draw();
        });

        $('#createForm').on('submit', function(event) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            event.preventDefault();

            Swal.fire({
                text: "Mohon menunggu..."
            });

            Swal.showLoading();

            $.ajax({
                url: $(this).attr("action"),
                method: "POST",
                data:  new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                success: (data) => {

                    if (data.status == "ok" && data.response == "successfully-created") {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: data.message,
                        });
                        setTimeout(function() {
                            Swal.close();
                            table.draw(false);
                            $('#modalCreate').modal('hide');
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
                        });
                    }
                }
            });
        });

        $(document).on('click', '.edit-item', function(event){
            event.preventDefault();

            let playerId = $(this).data("pid");
            let routeAction = "{{ route('admin.players.update', ':id') }}";
            routeAction = routeAction.replace(':id', playerId);

            let routeUrl = "{{ route('admin.players.edit', ':id') }}";
            routeUrl = routeUrl.replace(':id', playerId);

            $.ajax({
                type: "GET",
                url: routeUrl,
                success: function (response) {
                    $("#editForm").attr('action', routeAction);
           
                    $("#nama_edit").val(response.nama);
                    $("#nomor_punggung_edit").val(response.nomor_punggung);
                    $("#tinggi_badan_edit").val(response.tinggi_badan);
                    $("#berat_badan_edit").val(response.berat_badan);
                    $("#posisi_edit").val(response.posisi);

                    $('#modalEdit').modal('show');
                }
            });
        });

        $(document).on('click', '.delete-item', function(event){

            let playerId = $(this).data("pid");
            let routeUrl = "{{ route('admin.players.destroy', ':id') }}";
            routeUrl = routeUrl.replace(':id', playerId);

            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            event.preventDefault();
            Swal.fire({
                title: "Apakah yakin akan menghapus ini!?",
                // type: "info",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Hapus!",
                cancelButtonText: "Batal",
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#dc3545",
                // reverseButtons: true,
                focusConfirm: true,
                focusCancel: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.close();
                    Swal.fire({
                        text: "Mohon menunggu..."
                    });

                    Swal.showLoading();
                    $.ajax({
                        type:'DELETE',
                        url: routeUrl,
                        success:function(data)
                        {
                            if(data.status == "ok"){
                                table.draw(false);
                                Swal.close();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: data.message,
                                });
                                setTimeout(function() {
                                    Swal.close();
                                }, 1500);
                            }
                        },
                        error: function(data){
                        }
                    });
                }
            })
        });

        $('#editForm').on('submit', function(event) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            event.preventDefault();

            Swal.fire({
                text: "Mohon menunggu..."
            });

            Swal.showLoading();

            $.ajax({
                url: $(this).attr("action"),
                method: "POST",
                data:  new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                success: (data) => {

                    if (data.status == "ok" && data.response == "successfully-updated") {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: data.message,
                        });
                        setTimeout(function() {
                            Swal.close();
                            table.draw(false);
                            $('#modalEdit').modal('hide');
                            $("#modalEdit").load(location.href + " #modalEdit");
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
                        });
                    }
                }
            });
        });
    </script>
@endsection
