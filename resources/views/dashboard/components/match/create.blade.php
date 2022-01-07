@extends('dashboard.dashboard')
@section('custom_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard') }}/app-assets/css/plugins/forms/pickers/form-pickadate.css">
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
                            <h2 class="content-header-title float-left mb-0">Create Match</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.matchs.index') }}">Matchs</a>
                                    </li>
                                    <li class="breadcrumb-item active">Create Match
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic Horizontal form layout section start -->
                <section id="basic-horizontal-layouts">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Match Form</h4>
                                </div>
                                <div class="card-body">
                                    <form id="createForm" action="{{ route('admin.matchs.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="guest_team">Team Tuan Rumah
                                                <span style="color: red">*</span>
                                            </label>
                                            <select name="home_team" id="home_team" class="select2 form-control">
                                                <option value="">Pilih</option>
                                                @foreach ($teams as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="guest_team">Team Tamu
                                                <span style="color: red">*</span>
                                            </label>
                                            <select name="guest_team" id="guest_team" class="select2 form-control">
                                                <option value="">Pilih</option>
                                                @foreach ($teams as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="date_match">Tanggal Pertandingan
                                                <span style="color: red">*</span>
                                            </label>
                                            <input type="text" name="date_match" id="date_match" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                        </div>
                                        <div class="form-group">
                                            <label for="time_match">Waktu Pertandingan
                                                <span style="color: red">*</span>
                                            </label>
                                            <input type="time" name="time_match" id="time_match" class="form-control" placeholder="8:00 AM" />
                                        </div>
                                        <button type="reset" class="btn btn-danger" style="float: left" data-dismiss="modal">Batalkan</button>
                                        <button type="submit" class="btn btn-success" style="float: right">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic Horizontal form layout section end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

@section('custom_js')
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="{{ asset('dashboard') }}/app-assets/js/scripts/forms/pickers/form-pickers.js"></script>
    <script>

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
                        });
                    }
                }
            });
        });

    </script>
@endsection
