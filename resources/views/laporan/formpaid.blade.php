    @foreach($transaksi as $data)
    <!-- Modal -->
    <div class="modal fade" id="form-paid{{ $data->kode_transaksi }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-nomor="{{ $data->kode_transaksi }}">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
                    <h5 class="modal-title text-info" id="exampleModalLabel">Transaksi Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">

                            <form action="/{{auth()->user()->level}}/laporan/paid/{{ $data->kode_transaksi }}" method="POST"
                                id="form-paid">
                                @csrf
                                <select class="custom-select" name="kode_kasir" hidden>
                                    <option value="{{ auth()->user()->kode }}">
                                        {{ auth()->user()->nama }}
                                    </option>
                                </select>
                                <div class="form-group">
                                    <div class="input-group-prepend">
                                        <h1 class="text-info mr-2">Rp<br></h1>
                                        <h1 class="text-info" id="label-total-bayar">{{ $data->total }}</h1>
                                    </div>
                                    <input type="number" id="total-harganya" name="total-harganya" value="{{ $data->total }}" name="total-bayar" readonly hidden>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="bayar">Bayar</label>
                                            <input type="number" id="bayar" class="form-control jumlah" name="bayar" required>
                                            <div id="warning-message" style="color: red; display: none;">
                                                jumlah bayar kurang dari subtotal!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="kembali">Kembali</label>
                                            <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn m-1 btn-outline-primary float-right" data-toggle="modal"
                                    onclick="simpan()">Bayar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
