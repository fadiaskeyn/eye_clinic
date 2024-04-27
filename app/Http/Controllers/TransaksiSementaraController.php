<?php

namespace App\Http\Controllers;

use App\Models\TransaksiSementara;
use App\Models\TransaksiDetail;
use App\Models\Transaksi;
use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TransaksiSementaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $barang = Barang::all();
        $transaksi = Transaksi::all();
        $pasien = TransaksiSementara::pluck('pasien')->unique()->implode(', ');
        $diagnosa = TransaksiSementara::pluck('diagnosa')->implode(', ');
        $transaksi_sementara = TransaksiSementara::all();
        $now = Carbon::now();
        $tahun_bulan = $now->year . $now->month;
        $cek = Transaksi::count();

        if($cek == 0){
            $urut = 10000001;
            $nomor = $tahun_bulan . $urut;
        }else {
            $ambil = Transaksi::all()->last();
            $urut = (int)substr($ambil->kode_transaksi, -8) + 1;
            $nomor = $tahun_bulan . $urut;
        }
        // dd($request->all());
        return view('penjualan.index', compact('barang', 'transaksi_sementara', 'nomor', 'pasien','diagnosa'));

    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        $sub_total = ($data['harga'] - ($data['diskon'] * $data['harga'] / 100)) * $data['jumlah'];
        $barangAda = TransaksiSementara::where('barang_id', $request->barang_id)->first();
        if($barangAda) {
            return redirect('/' . $user->level . '/penjualan')->with('warning', 'Barang Yang Sama Sudah Tersedia');
        }else{
            $transaksi_sementara = new TransaksiSementara;
            $transaksi_sementara->kode_transaksi = $request->kode_transaksi;
            $transaksi_sementara->barang_id = $request->barang_id;
            $transaksi_sementara->harga = $request->harga;
            $transaksi_sementara->jumlah = $request->jumlah;
            $transaksi_sementara->diskon = $request->diskon;
            $transaksi_sementara->total = $sub_total;
            $transaksi_sementara->save();
        }
        return redirect('/' . $user->level . '/penjualan');
    }



    /**
     * Display the specified resource.
     */
    public function show(TransaksiSementara $transaksiSementara)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, $barang_id)
    {
        $user = Auth::user();

        $data = $request->all();
        $barang = Barang::find($barang_id);

        if($barang->stok >= $data['jumlah']){
            $sub_total = ($data['harga'] - ($data['diskon'] * $data['harga'] / 100)) * $data['jumlah'];

            $transaksi_sementara = TransaksiSementara::find($id);
            $transaksi_sementara->jumlah = $request->jumlah;
            $transaksi_sementara->total = $sub_total;
            $transaksi_sementara->update();
            return redirect('/' . $user->level . '/penjualan');
        }else{
            return redirect('/' . $user->level . '/penjualan')->with('gagal', $barang->nama . ' hanya tersisa ' . $barang->stok);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $transaksi_sementara = TransaksiSementara::find($id);
        $transaksi_sementara->delete();
        return redirect('/' . $user->level . '/penjualan');
    }

    public function hapusSemua()
    {
        $user = Auth::user();

        TransaksiSementara::truncate(); // Menghapus semua data dari tabel Transaksi
        return redirect('/' . $user->level . '/penjualan');
    }

     public function simpandiagnosa(Request $request, $nomor){
    $user = Auth::user();
    // Ambil semua entri TransaksiSementara dengan kode_transaksi yang sesuai
    $transaksisementara = TransaksiSementara::where('kode_transaksi', $nomor)->get();

    // Jika tidak ada entri yang ditemukan, kembalikan dengan pesan gagal
    if($transaksisementara->isEmpty()){
        return redirect('/'. $user->level. '/penjualan')->with('gagal', 'Tambahkan Obat/Service Terlebih Dahulu');
    }

    // Iterasi melalui setiap entri dan perbarui nilai pasien dan diagnosa
    foreach ($transaksisementara as $row) {
        $row->pasien = $request->input('pasien');
        $row->diagnosa = $request->input('diagnosa');
        $row->save();
    }
    // Setelah semua entri diperbarui, kembalikan dengan pesan berhasil
    return redirect('/'. $user->level. '/penjualan')->with('sukses', 'Berhasil Menyimpan Data');
}

public function bayar(Request $request, $kode_transaksi)
{
    $user = Auth::user();
    $tanggalSekarang = now('Asia/Jakarta')->format('Y-m-d H:i:s');
    $transaksi_sementara = TransaksiSementara::all();
    $status = "paid";
    if ($transaksi_sementara->isEmpty()) {
        return redirect('/' . $user->level . '/penjualan')->with('gagal', 'Transaksi Gagal');
    } else {
        try {
            $request->validate([
                'kode_transaksi' => 'required|string|max:255',
                'total' => 'numeric',
                'bayar' => 'numeric',
                'kembali' => 'numeric',
                'kode_kasir' => 'required|string|max:255',
                'pasien' => 'required|string',
                'diagnosa' => 'required|string',
            ]);

             Transaksi::create([
                'kode_transaksi' => $request->kode_transaksi,
                'total' => $request->total,
                'bayar' => $request->bayar,
                'kembali' => $request->kembali,
                'kode_kasir' => $request->kode_kasir,
                'tanggal' => 'created_at',
                'pasien' => $request->pasien,
                'diagnosa' => $request->diagnosa,
                'status' => $status,
            ]);

            foreach ($transaksi_sementara as $data) {
                $barang = Barang::find($data->barang_id);
                $kurangi = $barang->stok - $data->jumlah;
                $barang->update(['stok' => $kurangi]);
                TransaksiDetail::create([
                    'kode_transaksi' => $data->kode_transaksi,
                    'barang' => $barang->nama,
                    'harga' => $data->harga,
                    'jumlah' => $data->jumlah,
                    'diskon' => $data->diskon,
                    'total' => $data->total,
                    'diagnosa' => $data->diagnosa, // Mengubah $data->dignosa menjadi $data->diagnosa
                    'pasien' => $data->pasien, // Menambahkan penulisan pasien
                ]);
                TransaksiSementara::truncate();
            }
        }
        catch(\Exception $e){
            return redirect('/' . $user->level . '/penjualan')->with('gagal', 'Isi Product Terlebih Dahulu, Lalu Data Pasien' . $e->getMessage());
        }

        return redirect('/' . $user->level . '/penjualan');
    }
}
}
