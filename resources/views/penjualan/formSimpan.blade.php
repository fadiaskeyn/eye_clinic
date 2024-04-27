<!-- Modal -->
<div class="modal fade" id="form-simpan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalLabel">Simpan Hasil Diagnosa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form action="/{{auth()->user()->level}}/penjualan/simpan/{{ $nomor }}" method="POST" id="form-simpan">
                            @csrf
                            @method('POST')
                            <select class="custom-select" name="kode_kasir" hidden>
                                <option value="{{ auth()->user()->kode }}">
                                    {{ auth()->user()->nama }}
                                </option>
                            </select>
                            <input type="text" id="kode-transaksi" class="form-control" value="{{$nomor}}"
                                name="kode_transaksi" readonly hidden>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="pasien">Nama Pasien</label>
                                        <input type="text" id="pasien" class="form-control jumlah" name="pasien" required>
                                    </div>
                                    {{--  //Diagnosa  --}}
                                    <div class="form-group">
                                        <label for="diagnosa">Diagnosa</label>
                                        <textarea id="diagnosa" class="form-control jumlah" name="diagnosa" required style="height: 100px">
                                    </textarea>
                                    </div>
                                    <div class="row mb-2">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                </div>
                            </div>
                            <button type="submit" class="btn m-1 btn-outline-primary float-right" data-toggle="modal">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
