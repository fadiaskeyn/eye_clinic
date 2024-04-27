@extends('layout.app')
@section('title', ' - Laporan')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Laporan</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    @if(auth()->user()->level == 'admin')
                    <div class="card-header bg-white justify-content-center">
                        <form action="/{{auth()->user()->level}}/laporan/cari">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group d-flex">
                                        <label class="mr-1" for="nama">Dari</label>
                                        <input type="date" class="form-control" name="dari" id="tanggalDari" max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group d-flex">
                                        <label class="mr-1" for="nama">Sampai</label>
                                        <input type="date" class="form-control mr-5" name="sampai" id="tanggalSampai" max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-search"></i> Cari</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                    @if(auth()->user()->level == 'admin')
                    <div class="card-header bg-white">
                        <h4 class="text-primary">Riwayat Transaksi</h4>
                    </div>
                    @endif
                    <div class="card-body p-2">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Kode Dokter</th>
                                    <th>Total</th>
                                    <th hidden>Bayar</th>
                                    <th hidden>Kembali</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi as  $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->kode_transaksi}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>{{$item->kode_kasir}}</td>
                                    <td>{{$item->formatRupiah('total')}}</td>
                                    <td hidden>{{$item->formatRupiah('bayar')}}</td>
                                    <td hidden>{{$item->formatRupiah('kembali')}}</td>
                                    <td>
                                        <a href="/{{auth()->user()->level}}/laporan/{{$item->kode_transaksi}}"
                                            class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i> Detail</a>

                                            <a href="/{{auth()->user()->level}}/laporan/{{$item->kode_transaksi}}/print"
                                                class="btn btn-sm btn-outline-danger @if($item->status == 'paid') disabled @endif">
                                                 <i class="fa fa-print"></i> Print
                                             </a>

                                             <a data-target="#form-paid{{ $item->kode_transaksi }}" name="paid"
                                                class="btn btn-sm @if($item->status == 'succes') btn-outline-warning disabled @else btn-outline-success @endif paid-btn"
                                                data-toggle="modal" data-nomor="{{ $item->kode_transaksi }}" data-status="{{ $item->status }}">
                                                 <i class="fa-solid fa-money-bill-wave" style="color:
                                                     @if($item->status == 'succes') #2cf271 @else #ffcc00 @endif;"></i>
                                                 {{ $item->status }}
                                             </a>



                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('laporan.formpaid')
@endsection
@push('script')
    <script>
        $(document).ready(function () {
            $('.paid-btn').click(function () {
                var nomor = $(this).data('nomor');
                var status = $(this).data('status');
                $('#form-paid').data('nomor', nomor);
                $('#form-paid').find('.modal-title').text('Transaksi Pembayaran ' + status);

                // Mengambil nilai total dari input
                var totalBayar = parseFloat($('#total-harganya').val()) || 0;

                // Memperbarui teks pada elemen h1
                $('#label-total-bayar').text(totalBayar.toLocaleString('id-ID'));

                $('#form-paid').modal('show');
            });

            $('#bayar').on('input', function () {
                hitungKembali();
            });
        });

        function hitungKembali() {
            var totalBayar = parseFloat($('#total-harganya').val()) || 0;
            var bayar = parseFloat($('#bayar').val()) || 0;
            var kembali = bayar - totalBayar;
            $('#kembali').val(kembali.toLocaleString('id-ID'));
        }

        function simpan() {
            event.preventDefault();
            var bayar = parseFloat($('#bayar').val()) || 0;
            var kembali = parseFloat($('#kembali').val()) || 0;
            var form_bayar = $('#form-paid');

            if (bayar == 0) {
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
                }).then((bayar) => {
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

        $(document).ready(function () {
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $(this).removeData('bs.modal');
            });
        });
    </script>
@endpush
