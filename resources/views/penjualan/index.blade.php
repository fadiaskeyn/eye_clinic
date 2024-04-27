@extends('layout.app')

@section('title', ' - Penjualan')

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h4 class="position-absolute">Catatan Dokter</h4>
                        <div class="card-header-form float-right">
                            <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal"
                                data-target="#data-barang"><i class="fa fa-plus"></i> Tambah Obat</button>
                                <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal"
                                data-target="#modal-new"><i class="fa fa-plus"></i> Tambah Tindakan   </button>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="kasir">Kasir</label>
                                    <input class="custom-select" name="kode_kasir" value="{{ auth()->user()->nama }}">
                                        <input value="{{ auth()->user()->kode }}" hidden>
                                </input>
                                    </input>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
    <label for="kode">Kode Transaksi</label>
    <input type="text" id="kode-transaksi" class="form-control"
        name="kode_transaksi" value="{{ $nomor }}" readonly>
</div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                <label for="kode">Data Pasien</label>
                                <input type="text" id="pasien" class="form-control" value="{{$pasien}}" name="pasien" readonly required placeholder=" Click Me >,<' ">


                                <input type="text" id="diagnosa" class="form-control" value="{{$diagnosa}}" name="diagnosa" readonly hidden>

                                <input type="hidden" class="form-control" value="{{$pasien}}"
                                                    name="pasien" id="pasien"  >
                                                </td>

                                                <input type="hidden" class="form-control" value="{{$diagnosa}}"
                                                    name="diagnosa" id="diagnosa"  >
                                                </td>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group float-right">
                                    <label class="text-info" for="Total Belanja" hidden>Subtotal</label>
                                    <div class="input-group-prepend">
                                        <h1 class=" text-info mr-2" hidden>Rp<br></h1>
                                        <input class="d-none" type="text" id="total" value="0" name="total" hidden>
                                        <h1 class="text-info" id="label-total" hidden>0</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rounded" style="overflow-y: scroll; height: 300px;">
                            <table class="table table-bordered" id="table-transaksi">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th hidden>Harga</th>
                                        <th>Jumlah</th>
                                        <th hidden>Diskon</th>
                                        <th hidden>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksi_sementara as $item)
                                    <tr>
                                        <td>{{$item->barang->nama}}</td>
                                        <form action="/dokter/transaksi-sementara/{{$item->id}}/{{$item->barang_id}}/edit" method="POST">
                                            @csrf
                                            @method('PUT')
                                        <td class="harga" value="{{$item->harga}}" hidden>
                                            {{$item->formatRupiah('harga')}}
                                            <input type="text" value="{{$item->harga}}" name="harga" hidden>
                                        </td>
                                            <td class="jumlah" value="{{$item->jumlah}}" style="width: 20%">

                                                <input type="number" class="form-control" value="{{$item->jumlah}}"
                                                    name="jumlah" min="1" max="{{$item->stok}}">
                                                </td>

                                            <td class="diskon" value="{{$item->diskon}}" hidden>{{$item->diskon}}% <input type="text" value="{{$item->diskon}}" name="diskon" hidden></td>


                                            <td class="total" value="{{$item->total}}" hidden>
                                            {{$item->formatRupiah('total')}}</td>
                                            <td>
                                                <div class="aksi d-flex">
                                                    <button type="submit" class="btn btn-sm btn-warning mr-2"><i
                                                            class="fa fa-edit"></i></button>
                                                    </form>
                                                    <form action="/{{auth()->user()->level}}/penjualan/{{$item->id}}"
                                                        id="delete-form">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-sm btn-danger"><i
                                                                class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">

                        {{--  <button type="button" id="bayar-modal" class="btn m-1 btn-outline-primary float-right">Simpan</button>  --}}

                        <button type="button" id="bayar-modal" class="btn m-1 btn-outline-primary float-right"
                            data-toggle="modal" data-target="#form-bayar">Simpan</button>

                            {{--  <button type="button" id="simpan-modal" class="btn m-1 btn-outline-primary float-right"
                            data-toggle="modal" data-target="#form-simpan">Simpan</button>  --}}

                        <a href="/{{auth()->user()->level}}/penjualan/hapus/semua"
                            class="btn m-1 btn-outline-danger float-right">Batal</a>

                           {{--  <button type="button" class=""btn m-1 btn-outline-primary float-right"
                            data-toggle="modal" data-target="">
                            </button>  --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</section>
{{--  @include('penjualan.dataBarang')  --}}
@include('penjualan.formBayar')
@include('penjualan.formSimpan')
@include('penjualan.modaltransaksi')
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mengambil elemen input "Nama Pasien"
        var inputPasien = document.getElementById('pasien');

        // Menambahkan event listener untuk menanggapi klik pada input
        inputPasien.addEventListener('click', function() {
            // Memunculkan modal dengan ID "form-simpan"
            $('#form-simpan').modal('show');
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#table').DataTable();
    });

    $(document).ready(function () {
        $('.jumlah').on('input', function () {
            if ($(this).val() < 0) {
                $(this).val(0);
            }
        });
    });

    // Mengambil elemen input
    var inputAngka = document.getElementById('bayar');

    // Menambahkan event listener untuk setiap kali ada input
    inputAngka.addEventListener('input', function () {
        // Mengganti nilai input hanya dengan karakter angka
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    function hitungKembali() {
        // Ambil nilai dari input "Di Bayar"
        var diBayar = document.getElementById('bayar').value;

        // Konversi nilai menjadi angka
        var diBayarAngka = parseFloat(diBayar) || 0;

        // Ambil nilai total belanja dari label
        var total = document.getElementById('total').value;
        var belanja = parseFloat(total) || 0;

        // Hitung kembali
        var kembali = diBayarAngka - belanja;

        // Tampilkan nilai kembali pada input "Kembali"
        document.getElementById('kembali').value = kembali.toLocaleString('id-ID');
        document.getElementById('kembalian').value = kembali;
        total.value = kembali;

        // Optionally, you can show a warning message if the payment is insufficient
        if (kembali < 0) {
            document.getElementById('warning-message').style.display = 'block';
        } else {
            document.getElementById('warning-message').style.display = 'none';
        }
    }
    document.getElementById('bayar').addEventListener('input', hitungKembali);
</script>
<script>
    function simpan() {
        event.preventDefault()
        var bayar = parseFloat(document.getElementById('bayar').value) || 0;
        var kembali = parseFloat(document.getElementById('kembali').value) || 0;
        form_bayar = document.getElementById('form-penjualan');
        if (kembali < 0 || bayar == 0) {
            iziToast.warning({
                title: 'Transaksi Gagal',
                message: 'Jumlah Bayar Kurang !',
                position: 'topRight'
            });
        } else {
            swal({
                    title: 'Simpan Transaksi ?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((bayar) => {
                    if (bayar) {
                        form_bayar.submit();
                    } else {
                        iziToast.success({
                            title: 'Transaksi Dibatalkan',
                            position: 'topRight'
                        });
                    }
                });
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Menambahkan event listener untuk menangkap perubahan pada input "Nama Pasien"
        document.getElementById('pasien').addEventListener('input', function(event) {
            // Menyimpan nilai input "Nama Pasien" ke dalam variabel pasienValue
            var pasienValue = event.target.value;

            // Mengatur nilai input "pasien" di dalam form di dalam modal sesuai dengan nilai yang ditangkap
            document.getElementById('inputPasien').value = pasienValue;
        });
    });
</script>

<script>
    function hitungTotal() {
        var total = document.querySelectorAll('#table-transaksi tbody td.total');
        var label_total = document.getElementById('label-total');
        var sub_total = document.getElementById('total');
        var label_total_bayar = document.getElementById('label-total-bayar');
        var sub_total_bayar = document.getElementById('total-bayar');
        var bayarButton = document.getElementById('bayar-modal');

        // Inisialisasi variabel total
        var grandTotal = 0;

        // Iterasi melalui setiap elemen dan menjumlahkannya
        total.forEach(function (element) {
            var totalValue = parseFloat(element.getAttribute('value')) || 0;
            grandTotal += totalValue;
        });

        if (grandTotal == 0) {
            bayarButton.setAttribute('disabled', true);
        } else {
            bayarButton.removeAttribute('disabled');
        }

        // Tampilkan hasilnya di label_total dengan format mata uang Rupiah
        label_total.innerHTML = grandTotal.toLocaleString('id-ID');
        sub_total.value = grandTotal;
        label_total_bayar.innerHTML = grandTotal.toLocaleString('id-ID');
        sub_total_bayar.value = grandTotal;
    }
</script>
<script>
    function setDibayarkan(setbayar) {
        bayar = parseFloat(document.getElementById('bayar').value) || 0;
        var total = 0;

        if (setbayar == 0) {
            bayar.value = "";
        } else {
            if (bayar == 0) {
                var hasil = total += setbayar;
            } else {
                var hasil = total + setbayar + bayar;
            }
        }


        document.getElementById('bayar').value = hasil;
        hitungKembali();
    }
</script>

@endpush

