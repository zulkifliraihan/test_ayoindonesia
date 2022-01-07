@extends('dashboard.dashboard')
@section('custom_css')
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
                            <h2 class="content-header-title float-left mb-0">Data Report</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.matchs.index') }}">Matchs</a>
                                    </li>
                                    <li class="breadcrumb-item active">Data Report
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                    <div class="form-group breadcrumb-right">
                        <div class="dropdown">
                            <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle waves-effect waves-float waves-light" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg></button>
                            <div class="dropdown-menu dropdown-menu-right" style="">
                                <a class="dropdown-item" href="{{ route('admin.matchs.report.edit', $match->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square mr-1">
                                        <polyline points="9 11 12 14 22 4"></polyline>
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                    </svg>
                                    <span class="align-middle">
                                        Edit Report Ini
                                    </span>
                                </a>
                                <a class="dropdown-item delete-item" href="javascript:void(0)" data-mid="{{ $match->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square mr-1">
                                        <polyline points="9 11 12 14 22 4"></polyline>
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                    </svg>
                                    <span class="align-middle">
                                        Delete Report Ini
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Ajax Sourced Server-side -->
                <section id="ajax-datatable">
                    <div class="card card-congratulations">
                        <div class="card-body text-center">
                            <img src="{{ asset('dashboard') }}/app-assets/images/elements/decore-left.png" class="congratulations-img-left" alt="card-img-left">
                            <img src="{{ asset('dashboard') }}/app-assets/images/elements/decore-right.png" class="congratulations-img-right" alt="card-img-right">
                            <div class="avatar avatar-xl bg-primary shadow">
                                <div class="avatar-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award font-large-1"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                                </div>
                            </div>
                            @if ($teamWin != false)
                                <div class="text-center">
                                    <h1 class="mb-1 text-white">
                                            Congratulations Team {{ $teamWin }},
                                    </h1>
                                    <p class="card-text m-auto w-75">
                                        Atas memenangkan pertandingan pada tanggal {{ Carbon\Carbon::parse($match->date_match)->format('d F Y') }}
                                        antara {{ $match->hometeam->nama }} vs {{ $match->guestteam->nama }} dengan hasil {{ $match->total_skor }}
                                    </p>
                                </div>
                            @else
                                <div class="text-center">
                                    <h1 class="mb-1 text-white">
                                            Pertandingan Seri,
                                    </h1>
                                    <p class="card-text m-auto w-75">
                                        Pertandingan pada tanggal {{ Carbon\Carbon::parse($match->date_match)->format('d F Y') }}
                                        antara {{ $match->hometeam->nama }} vs {{ $match->guestteam->nama }} dengan hasil Seri
                                    </p>
                                </div>

                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom">
                                    <h1>Pemain Pencetak Goal</h1>
                                </div>
                                <div class="card-datatable datatable-user-view">
                                    <table id="table-matchs" class="datatables-ajax table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pemain</th>
                                                <th>Nomor Punggung</th>
                                                <th>Waktu Goal</th>
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

    <script>
        var table;

        $(function() {
            table = $('#table-matchs').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                        url: "{{ route('admin.matchs.report.index', $match->id) }}",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className : "text-center width-100"},
                    {data: 'nama_pemain', name: 'tuanrumah'},
                    {data: 'nomor_punggung_pemain', name: 'nomor_punggung_pemain'},
                    {data: 'waktu_goal', name: 'waktu_goal'},
                ]
            });
            table.on( 'order.dt search.dt', function () {
                table.column(0, {order:'applied', search:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                });
            }).draw();
        });

        $(document).on('click', '.delete-item', function(event){

            let matchId = $(this).data("mid");
            let routeUrl = "{{ route('admin.matchs.report.destroy', ':id') }}";
            routeUrl = routeUrl.replace(':id', matchId);

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
                                    window.location.href = data.route;
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
