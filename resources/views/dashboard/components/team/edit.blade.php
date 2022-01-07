@extends('dashboard.dashboard')
@section('custom_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css" integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                            <h2 class="content-header-title float-left mb-0">Edit Team</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">

                                    <li class="breadcrumb-item"><a href="{{ route('admin.teams.index') }}">Teams</a>
                                    </li>
                                    <li class="breadcrumb-item active">Edit Team
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
                                    <h4 class="card-title">Team Form</h4>
                                </div>
                                <div class="card-body">
                                    <form id="editForm" action="{{ route('admin.teams.update', $team->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="nama">Nama Team
                                                <span style="color: red">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="nama" id="nama" value="{{ $team->nama }}" />
                                        </div>
                                        <div class="form-group">
                                            <label for="tahun">Tahun Berdiri
                                                <span style="color: red">*</span>
                                            </label>
                                            <input type="number" class="form-control" name="tahun" id="tahun" value="{{ $team->tahun }}" />
                                        </div>
                                        <div class="form-group">
                                            <label for="provinsi_id">Provinsi
                                                <span style="color: red">*</span>
                                            </label>
                                            <select name="provinsi_id" id="provinsi_id" class="select2 form-control">
                                                <option value="">Pilih</option>
                                                @foreach ($provinces as $item)
                                                    <option value="{{ $item->id }}" @if ($item->id == $team->provinsi_id) selected @endif>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="kota_id">Kota
                                                <span style="color: red">*</span>
                                            </label>
                                            <select name="kota_id" id="kota_id" class="select2 form-control">
                                                <option value="">Pilih</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">Alamat Lengkap
                                                <span style="color: red">*</span>
                                            </label>
                                            <textarea name="alamat" id="alamat" rows="2" class="form-control">{{ $team->alamat }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="foto">Upload Logo Team</label>
                                            <input type="file" class="dropify" name="foto" id="foto" />
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var dropify = $('.dropify').dropify();

        $(document).ready(function () {
            let provinsi_id = $('#provinsi_id').val();
            $.ajax({
                type: "GET",
                url: '/kota/' + provinsi_id,
                dataType: "json",
                success: function (response) {
                    $('#kota_id').empty();
                    $.each(response, function (key, value) {
                        $('#kota_id').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
            $("#kota_id").val("{{ $team->kota_id }}");
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
