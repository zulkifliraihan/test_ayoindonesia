<div class="modal fade text-left" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Tambah Baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createForm" action="{{ route('admin.players.store') }}">
                    @csrf
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

<div class="modal fade text-left" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Tambah Baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_edit">Nama Pemain
                            <span style="color: red">*</span>
                        </label>
                        <input type="text" class="form-control" name="nama" id="nama_edit" />
                    </div>
                    <div class="form-group">
                        <label for="nomor_punggung_edit">Nomor Punggung
                            <span style="color: red">*</span>
                        </label>
                        <input type="number" class="form-control" name="nomor_punggung" id="nomor_punggung_edit" />
                    </div>
                    <div class="form-group">
                        <label for="tinggi_badan_edit">Tinggi Badan
                            <span style="color: red">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="tinggi_badan" id="tinggi_badan_edit">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">CM</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="berat_badan_edit">Berat Badan
                            <span style="color: red">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="berat_badan" id="berat_badan_edit">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">KG</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="posisi_edit">Posisi
                            <span style="color: red">*</span>
                        </label>
                        <select name="posisi" id="posisi_edit" class="form-control">
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
