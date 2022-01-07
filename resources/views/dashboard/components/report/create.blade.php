@extends('dashboard.dashboard')
@section('custom_css')

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
                            <h2 class="content-header-title float-left mb-0">Create Report</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">

                                    <li class="breadcrumb-item"><a href="{{ route('admin.matchs.index') }}">Matchs</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.matchs.report.index', $match->id) }}">Reports</a>
                                    </li>
                                    <li class="breadcrumb-item active">Create Report
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
                                    <h4 class="card-title">
                                        Report Form for Match
                                        {{ $match->hometeam->nama }}
                                        vs
                                        {{ $match->guestteam->nama }}
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <form class="invoice-repeater" id="createForm" action="{{ route('admin.matchs.report.store', $match->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="skor_home_team">Skor Team Tuan Rumah ({{ $match->hometeam->nama }})
                                                <span style="color: red">*</span>
                                            </label>
                                            <input type="number" name="skor_home_team" id="skor_home_team" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label for="skor_guest_team">Skor Team Tamu ({{ $match->guestteam->nama }})
                                                <span style="color: red">*</span>
                                            </label>
                                            <input type="number" name="skor_guest_team" id="skor_guest_team" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label for="time_match" class="mb-2">Data Cetak Gol
                                                <span style="color: red">*</span>
                                            </label>
                                            <div data-repeater-list="invoice">
                                                <div data-repeater-item>
                                                    <div class="row d-flex align-items-end">
                                                        <div class="col-md-4 col-12">
                                                            <div class="form-group">
                                                                <label for="player_id">Nama Pemain</label>
                                                                <select name="player_id[]" id="player_id" class=" form-control">
                                                                    <option value=""></option>
                                                                    @foreach ($players as $item )
                                                                        <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 col-12">
                                                            <div class="form-group">
                                                                <label for="time_goal">Waktu</label>
                                                                <input type="time" class="form-control" name="time_goal[]" id="time_goal" aria-describedby="time_goal" />
                                                            </div>
                                                        </div>


                                                        <div class="col-md-4 col-12">
                                                            <div class="form-group">
                                                                <button class="btn btn-outline-danger text-nowrap px-1" data-repeater-delete type="button">
                                                                    <i data-feather="x" class="mr-25"></i>
                                                                    <span>Delete</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                                        <i data-feather="plus" class="mr-25"></i>
                                                        <span>Add New</span>
                                                    </button>
                                                </div>
                                            </div>
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
    <script src="{{ asset('dashboard') }}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>

    <script>
        $(function () {
            'use strict';

            // form repeater jquery
            $('.invoice-repeater, .repeater-default').repeater({
                show: function () {
                $(this).slideDown();
                // Feather Icons
                if (feather) {
                    feather.replace({ width: 14, height: 14 });
                }
                },
                hide: function (deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
                }
            });
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
