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
                            <h2 class="content-header-title float-left mb-0">Create Player</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.players.index') }}">Players</a>
                                    </li>
                                    <li class="breadcrumb-item active">Create Player
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
                                    <h4 class="card-title">Player Form</h4>
                                </div>
                                <div class="card-body">
                                    <form id="createForm" action="{{ route('admin.players.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="team_id">Nama Team
                                                <span style="color: red">*</span>
                                            </label>
                                            <select name="team_id" id="team_id" class="select2 form-control">
                                                <option value="">Pilih</option>
                                                @foreach ($teams as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama">Nama Pemain
                                                <span style="color: red">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="nama" id="nama" />
                                        </div>
                                        <div class="form-group">
                                            <label for="nomor_punggung">Nomor Punggung
                                                <span style="color: red">*</span>
                                            </label>
                                            <input type="number" class="form-control" name="nomor_punggung" id="nomor_punggung" />
                                        </div>
                                        <div class="form-group">
                                            <label for="tinggi_badan">Tinggi Badan
                                                <span style="color: red">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="tinggi_badan" id="tinggi_badan">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">CM</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="berat_badan">Berat Badan
                                                <span style="color: red">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="berat_badan" id="berat_badan">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">KG</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="posisi">Posisi
                                                <span style="color: red">*</span>
                                            </label>
                                            <select name="posisi" id="posisi" class="form-control">
                                                <option value="">Pilih</option>
                                                <option value="Penyerang">Penyerang</option>
                                                <option value="Gelandang">Gelandang</option>
                                                <option value="Bertahan">Bertahan</option>
                                                <option value="Penjaga Gawang">Penjaga Gawang</option>
                                            </select>
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
