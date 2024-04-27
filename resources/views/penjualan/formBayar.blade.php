<!-- Modal -->
<div class="modal fade" id="form-bayar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <form action="/{{auth()->user()->level}}/penjualan/bayar/{{$nomor}}" method="POST" id="form-penjualan">
                            @csrf
                            <input type="hidden" name="kode_kasir" value="{{ auth()->user()->kode }}">
                            <input type="hidden" id="kode-transaksi" class="form-control" value="{{$nomor}}" name="kode_transaksi" readonly>

                            <div class="form-group">
                                <input type="hidden" id="total-bayar" value="0" name="total">
                                <h1 class="text-info" id="label-total-bayar" hidden>0</h1>
                            </div>

                            <div class="form-group">
                                <input type="text" name="pasien" id="pasien" class="form-control" value="{{$pasien}}" readonly>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" value="{{$diagnosa}}" name="diagnosa" id="diagnosa" readonly>
                            </div>

                            <div class="form-group">
                                <h1 class="text-info">Kirim Data ke Administrasi?</h1>
                            </div>

                            <button type="submit" class="btn m-1 btn-outline-primary float-right">Simpan</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
