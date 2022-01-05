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
                            <h2 class="content-header-title float-left mb-0">Data Teams</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Data Teams
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                    <div class="form-group breadcrumb-right">
                        <div class="dropdown">
                            <a href="{{ route('admin.teams.create') }}">
                                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle">
                                    <i data-feather="plus"></i>
                                </button>
                            </a>
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
                                    <table id="table-teams" class="datatables-ajax table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Team</th>
                                                <th>Tahun Berdiri</th>
                                                <th>Alamat Team</th>
                                                <th>Foto</th>
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

@endsection
@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var dropify = $('.dropify').dropify();
        var table;

        $(function() {
            table = $('#table-teams').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                        url: "{{ route('admin.teams.index') }}",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className : "text-center width-100"},
                    {data: 'nama', name: 'nama'},
                    {data: 'tahun', name: 'tahun'},
                    {data: 'alamat_lengkap', name: 'alamat_lengkap'},
                    {data: 'foto', name: 'foto'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, className : "text-center"},
                ]
            });
            table.on( 'order.dt search.dt', function () {
                table.column(0, {order:'applied', search:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                });
            }).draw();
        });

        $(document).on('click', '.delete-item', function(event){

            let teamId = $(this).data("tid");
            let routeUrl = "{{ route('admin.teams.destroy', ':id') }}";
            routeUrl = routeUrl.replace(':id', teamId);

            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            event.preventDefault();
            Swal.fire({
                title: "Apakah yakin akan menghapus ini!?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Hapus!",
                cancelButtonText: "Batal",
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#dc3545",
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
                            if(data.status == "ok" && data.response == "successfully-deleted"){
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

    </script>
@endsection
